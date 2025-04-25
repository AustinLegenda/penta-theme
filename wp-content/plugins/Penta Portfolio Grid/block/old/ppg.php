<?php
// Function to determine the container class based on item count
if (!function_exists('getContainerClass')) {
    function getContainerClass($containerIndex, $itemCount, $fiveItemContainersCount) {
        switch ($itemCount) {
            case 1:
                return "one-col"; // Single item layout
            case 2:
                return "two-col"; // Two items layout
            case 3:
                return "three-col"; // Three items layout
            case 4:
                return "two-col"; // Four items layout
            case 5:
                return ($fiveItemContainersCount % 2 === 1) ? "one-third-col-rev" : "one-third-col"; // Toggle layout for containers with five items
            default:
                return ""; // Fallback for unexpected item counts
        }
    }
}

// Function to render items for each container, applying big and reverse classes where necessary
if (!function_exists('renderContainerItems')) {
    function renderContainerItems($items, $fiveItemContainersCount) {
        $output = '';
        foreach ($items as $index => $item) {
            // Determine if item should have big and reverse classes
            $isBig = count($items) === 5 && $index === 0;
            $isReverse = $isBig && $fiveItemContainersCount % 2 === 0;

            // Add appropriate classes
            $itemClass = 'item-folio' . ($isBig ? ' --big' : '') . ($isReverse ? ' reverse' : '');

            // Wrap item with updated class and output
            $output .= '<div class="' . esc_attr($itemClass) . '">' . $item . '</div>';
        }
        return $output;
    }
}

// Fetch block attributes
$objectPositions = isset($attributes['objectPositions']) ? $attributes['objectPositions'] : [];
$paddingLeftRight = isset($attributes['paddingLeftRight']) ? esc_attr($attributes['paddingLeftRight']) : '30px';
$paddingTop = isset($attributes['paddingTop']) ? esc_attr($attributes['paddingTop']) : '75px';
$paddingBottom = isset($attributes['paddingBottom']) ? esc_attr($attributes['paddingBottom']) : '0px';

// Prepare WP_Query arguments
// Extract category IDs from the attribute array of objects
$selected = array_map(
    function( $cat ) {
        return isset( $cat['id'] ) ? intval( $cat['id'] ) : 0;
    },
    $attributes['selectedCategories'] ?? []
);

// Filter out any zeros or invalid entries
$category_ids = array_filter( $selected );

// Build query args
$args = [
    'post_type'      => 'any',
    'posts_per_page' => intval( $attributes['numberOfItems'] ?? 5 ),
    'order'          => $attributes['order']   ?? 'DESC',
    'orderby'        => $attributes['orderBy'] ?? 'date',
];

// Only add the category filter if we have at least one
if ( ! empty( $category_ids ) ) {
    $args['category__in'] = $category_ids;
}

$folio = new WP_Query( $args ); // Execute query with the prepared arguments

if ($folio->have_posts()) :
    $containers = [];
    $itemsPerContainer = 5; // Number of items per container
    $currentContainer = [];
    $fiveItemContainersCount = 0; // Track containers with five items

    while ($folio->have_posts()) : $folio->the_post();
        $currentIndex = $folio->current_post;

        // Get the object position for the current post, default to center (50% 50%)
        $objectPositionX = isset($objectPositions[$currentIndex]['x']) ? $objectPositions[$currentIndex]['x'] : 50;
        $objectPositionY = isset($objectPositions[$currentIndex]['y']) ? $objectPositions[$currentIndex]['y'] : 50;
        $objectPositionStyle = "object-position: {$objectPositionX}% {$objectPositionY}%;";

        // Build the individual item HTML with object-position style
        $item = '<div class="folio-snippet">';
        $item .= '<a class="folio-els-container" href="' . get_permalink() . '">';
        $item .= '<img style="width:100%; height:100%; object-fit:cover; ' . esc_attr($objectPositionStyle) . '" src="' . get_the_post_thumbnail_url() . '" alt="' . esc_attr(get_the_title()) . '">';
        $item .= '<div class="folio-intro">';
        $item .= sprintf(
            '<%1$s style="color:%2$s;">%3$s</%1$s>',
            esc_html($attributes['tagName'] ?? 'h4'), // Dynamic heading level (h1, h2, etc.)
            esc_attr($attributes['textColor'] ?? '#222'), // Color for the text
            get_the_title() // Post title
        );
        $item .= '</div></a></div>';

        // Add item to the current container
        $currentContainer[] = $item;

        // When the container is full or on the last post, close the container
        if (count($currentContainer) === $itemsPerContainer || $folio->current_post === $folio->post_count - 1) {
            $containerClasses = getContainerClass(count($containers), count($currentContainer), $fiveItemContainersCount);

            if (count($currentContainer) === 5) {
                $fiveItemContainersCount++; // Increment counter for five-item containers
            }

            // Render container with its items
            $containers[] = '<div class="folio-container nav-toggle ' . esc_attr($containerClasses) . '">' . renderContainerItems($currentContainer, $fiveItemContainersCount) . '</div>';
            $currentContainer = []; // Reset for the next container
        }
    endwhile;

    // Output the folio-wrapper with padding styles and all containers
    echo '<span id="work"></span>';
    echo '<div class="folio-wrapper" style="padding-left:' . $paddingLeftRight . '; padding-right:' . $paddingLeftRight . '; padding-top:' . $paddingTop . '; padding-bottom:' . $paddingBottom . ';">';
    echo implode('', $containers);
    echo '</div>';

else :
    echo '<p>No posts found.</p>';
endif;

wp_reset_postdata();
?>

<?php

/**
 * Front‑end template for Penta Blog Block using jQuery AJAX.
 *
 * This template applies block attributes for padding, text color, tag name, and object positions.
 */

// 1) Attributes
$menu_location      = $attributes['menuLocation']       ?? 'ppgMenu';
$objectPositions    = isset($attributes['objectPositions']) ? $attributes['objectPositions'] : [];
$paddingLeftRight   = isset($attributes['paddingLeftRight']) ? esc_attr($attributes['paddingLeftRight']) : '35px';
$paddingTop         = isset($attributes['paddingTop'])       ? esc_attr($attributes['paddingTop'])       : '30px';
$paddingBottom      = isset($attributes['paddingBottom'])    ? esc_attr($attributes['paddingBottom'])    : '0px';
$textColor          = isset($attributes['textColor'])        ? esc_attr($attributes['textColor'])        : '#222';
$tagName            = isset($attributes['tagName'])          ? esc_html($attributes['tagName'])            : 'h4';

// Build outer container style
$container_style = 'padding-left: ' . $paddingLeftRight . '; padding-right: ' . $paddingLeftRight .
  '; padding-top: ' . $paddingTop . '; padding-bottom: ' . $paddingBottom . '; color: ' . $textColor . ';';

// 2) Load menu items
$locations = get_nav_menu_locations();
if (empty($locations[$menu_location])) {
  echo '<p>No menu assigned.</p>';
  return;
}
$menu_obj   = wp_get_nav_menu_object($locations[$menu_location]);
$menu_items = wp_get_nav_menu_items($menu_obj->term_id);
if (empty($menu_items)) {
  echo '<p>Menu is empty.</p>';
  return;
}

// 3) Extract category IDs for "All"
$all_ids = [];
foreach ($menu_items as $item) {
  if ('category' === $item->object) {
    $all_ids[] = (int) $item->object_id;
  }
}
if (empty($all_ids)) {
  echo '<p>No categories in menu.</p>';
  return;
}
?>

<div id="work"></div>

<div class="ppg-menu-wrapper">
  <nav class="ppg-menu-container" aria-label="Category Menu">
    <ul>
      <li>
        <a class="ppg-menu-item" data-category="" href="#">
          <h5>Test</h5>
        </a>
      </li>
      <?php foreach ($menu_items as $item) :
        if ('category' !== $item->object) continue;
        $cat_id = (int) $item->object_id;
      ?>
        <li>
          <a class="ppg-menu-item" data-category="<?php echo $cat_id; ?>" href="#">
            <h5><?php echo esc_html($item->title); ?></h5>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
</div>

<!-- Posts Container -->

<div class="ppg-wrapper" style="<?php echo $container_style; ?>">
  <!-- AJAX Target Container -->
  <div class="ppg-container">
    <div class="ppg-item-wrapper">
      <?php
      $x = 0;
      // 1) Build your dynamic post type list:
      $post_types = get_post_types(
        [
          'public'               => true,
          'exclude_from_search'  => false,
        ],
        'names'
      );
      

      // 2) Remove the ones you don’t want:
      unset($post_types['page']);
      $folio = new WP_Query(array(
        'post_type'           => array_values($post_types),
        'category__in'        => $all_ids,
        'category__not_in'    => [1], // Exclude Uncategorized
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
        'order'               => 'DESC',
        'posts_per_page'      => -1,
      ));

      while ($folio->have_posts()) :
        $folio->the_post();
        // Use WP_Query's current_post property which is zero-indexed.
        $currentIndex = $folio->current_post;

        // Get the object position for the current post, default to center (50% 50%)
        $objectPositionX = isset($objectPositions[$currentIndex]['x']) ? $objectPositions[$currentIndex]['x'] : 50;
        $objectPositionY = isset($objectPositions[$currentIndex]['y']) ? $objectPositions[$currentIndex]['y'] : 50;
        $objectPositionStyle = "object-position: {$objectPositionX}% {$objectPositionY}%;";

        // Output the post thumbnail with the inline style for object position.
        echo '<div class="ppg-item-size">';
        echo '<div class="ppg-item-container">';
        echo '<a class="ppg-item" href="' . get_the_permalink() . '">';
        // Get the post thumbnail HTML.
        $thumbHTML = get_the_post_thumbnail(get_the_ID(), 'large');
        if ($thumbHTML) {
          // Inject the object-position style into the img tag.
          $thumbHTML = str_replace('<img', '<img style="width:100%; height:100%; object-fit:cover; ' . $objectPositionStyle . '"', $thumbHTML);
          echo $thumbHTML;
        }
        echo '<div class="ppg-item-title">';
        echo '<h4 style="color:' . $textColor . '; margin:0;">' . get_the_title() . '</h4>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
        echo '</div>';

        // Handle closing/opening of container groups as needed...
        if (($currentIndex + 1) % 5 === 0) {
          echo '</div><div class="ppg-item-wrapper">';
        }
      endwhile;
      wp_reset_postdata();
      ?>
    </div>
  </div>
</div>

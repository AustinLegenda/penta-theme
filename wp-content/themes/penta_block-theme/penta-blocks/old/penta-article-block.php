<?php
// Fetch categories for the current post
    $categories = get_the_category();
    $category_names = [];

    if ( ! empty( $categories ) ) {
        foreach ( $categories as $category ) {
            $category_names[] = esc_html( $category->name ); // Get the category name and escape it
        }
        $category_list = implode( ', ', $category_names ); // Join category names by commas
    } else {
        $category_list = 'Uncategorized'; // Default fallback if no categories are found
    }

    // Output the block HTML with dynamic category names
    ?>
    <div class="main-grid">
        <?php require get_template_directory() . '/penta-blocks/penta-nav.php'; // Include the navigation ?>
        <article class="main-grid-full">
            <header>
                <div class="penta-article-group">
                    <div class="penta-article-column">
                        <h1><?php echo esc_html( get_the_title() ); ?></h1>
                        <h5><?php echo $category_list; ?></h5> <!-- Output category names -->
                    </div>
                    <div class="penta-article-column">
                        <h3><?php echo esc_html( get_the_excerpt() ); ?></h3>
                    </div>
                </div>
            </header>
            <main>
                <?php echo $content; // Output InnerBlocks content ?>
            </main>
        </article>
    </div>
<?php
/**
 * For filtering posts by category 
 * credit: https://weichie.com/blog/wordpress-filter-posts-with-ajax/
**/ 

add_action( 'wp_ajax_nopriv_filter', 'filter' );
add_action( 'wp_ajax_filter', 'filter' );
function filter() {
    // Sanitize input
    $category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

    // Base args
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => -1,
        
    ];

    // Only filter if a non‑empty category was passed
    if ( $category !== '' ) {
        $args['category__in'] = [ absint( $category ) ];
      } else {
        // "All" clicked: exclude Uncategorized (ID 1)
        $args['category__not_in'] = [ 1 ];
    }

    $query = new WP_Query( $args );

    $response = '';

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            // Capture your snippet template output
            $response .= get_template_part( 'inc/snippet-post' );
        }
        wp_reset_postdata();
    } else {
        $response = 'empty';
    }

    // Return the HTML
    echo $response;
    wp_die(); // this is required to terminate immediately and return a proper response
}

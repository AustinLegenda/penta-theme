<?php

/**
 * Register a folio post type, with REST API support
 *
 * Based on example at: https://codex.wordpress.org/Function_Reference/register_post_type
 */
add_action('init', 'CustomPosts');
function customPosts()
{
    register_post_type( 'folio', 
    array(
    'public' => true,
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    'taxonomies' => array(
      'recordings', 'category', 'whatever'
    ),
    'labels' => array(
      'name' => 'Folio',
      'add_new_item' => 'Add New Project',
      'edit_item' => 'Edit Project',
      'all_items' => 'All Projects',
      'singular_name' => 'folio'
    ),
    'has_archive' => 'true',
    'menu_icon' => 'dashicons-portfolio'
    ));
}
//display custom post type on index.php https://toolset.com/forums/topic/how-to-display-custom-post-type-in-index/
function exclude_category( $query ) {
  if ( ($query->is_home() ||  $query->is_category()) && $query->is_main_query() ) {
      $query->set( 'post_type', array( 'post', 'folio' ) );
  }
}
add_action( 'pre_get_posts', 'exclude_category' );
/**
 * Register custom Navigation
 *
 * https://developer.wordpress.org/themes/functionality/navigation-menus/
 */
function register_my_menus() {
  register_nav_menus(
    array(
      'mainNav' => __( 'Header Menu Location' ),
      'catNavOne' => __( 'Category Menu One' ),
     )
   );
 }
 add_action( 'init', 'register_my_menus' );

/**
 * Register custom endpoint for fetching all public post types with filtering options.
 */
add_action( 'rest_api_init', 'custom_api_get_all_posts' );

function custom_api_get_all_posts() {
    register_rest_route( 'custom/v1', '/all-posts', array(
        'methods' => 'GET',
        'callback' => 'custom_api_get_all_posts_callback',
        'args' => array(
            'categories' => array(
                'required' => false,
                'validate_callback' => 'is_valid_category_ids',
            ),
            'per_page' => array(
                'required' => false,
                'validate_callback' => 'is_numeric',
                'default' => 10,
            ),
            'orderby' => array(
                'required' => false,
                'default' => 'date',
                'validate_callback' => function($param, $request, $key) {
                    $allowed_orderby = array('date', 'title', 'name', 'modified', 'rand');
                    return in_array($param, $allowed_orderby);
                },
            ),
            'order' => array(
                'required' => false,
                'default' => 'desc',
                'validate_callback' => function($param, $request, $key) {
                    return in_array(strtolower($param), array('asc', 'desc'));
                },
            ),
            'page' => array(
                'required' => false,
                'validate_callback' => 'is_numeric',
                'default' => 1,
            ),
        ),
    ));
}

// Custom validation for category IDs
function is_valid_category_ids( $param, $request, $key ) {
    $category_ids = explode( ',', $param );
    foreach ( $category_ids as $cat_id ) {
        if ( ! is_numeric( $cat_id ) || ! term_exists( (int) $cat_id, 'category' ) ) {
            return false;
        }
    }
    return true;
}

function custom_api_get_all_posts_callback( $request ) {
    // Initialize the array that will receive the posts' data.
    $posts_data = array();

    // Get query parameters with default fallbacks.
    $paged = $request->get_param( 'page' );
    $paged = isset( $paged ) ? (int) $paged : 1;
    $categories = $request->get_param( 'categories' );
    $per_page = (int) $request->get_param( 'per_page' );
    $orderby = $request->get_param( 'orderby' );
    $order = $request->get_param( 'order' );

    // Get all public post types, excluding 'attachment'.
    $post_types = get_post_types( array( 'public' => true, 'exclude_from_search' => false ), 'names' );
    unset( $post_types['attachment'] );

    // Build the arguments for WP_Query.
    $args = array(
        'paged'          => $paged,
        'posts_per_page' => $per_page,
        'post__not_in'   => get_option( 'sticky_posts' ),
        'post_type'      => array_values( $post_types ),
        'orderby'        => $orderby,
        'order'          => $order,
    );

    // If categories are provided, filter by category.
    if ( ! empty( $categories ) ) {
        $args['category__in'] = array_map( 'intval', explode( ',', $categories ) );
    }

    // Query the posts.
    $posts = get_posts( $args );

    // Loop through the posts and format the data.
    foreach ( $posts as $post ) {
        $id = $post->ID;
        $post_thumbnail = ( has_post_thumbnail( $id ) ) ? get_the_post_thumbnail_url( $id ) : null;

        // Get categories associated with the post
        $post_categories = get_the_category( $id );
        $categories_data = array_map( function( $cat ) {
            return array(
                'id'   => $cat->term_id,
                'name' => $cat->name,
                'slug' => $cat->slug,
            );
        }, $post_categories );

        $posts_data[] = array(
            'id'              => $id,
            'slug'            => $post->post_name,
            'type'            => $post->post_type,
            'title'           => $post->post_title,
            'featured_img_src'=> $post_thumbnail,
            'categories'      => $categories_data, // Include categories in the response
        );
    }

    // Return the array of post data.
    return rest_ensure_response( $posts_data );
}

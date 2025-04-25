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
      'secondNav' => __( 'Secondary Menu Location' )
     )
   );
 }
 add_action( 'init', 'register_my_menus' );

/**
*custom media field 
 */

 
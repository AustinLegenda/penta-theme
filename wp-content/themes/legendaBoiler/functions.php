<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/legendaBoiler/scripts/enqueue.php');
require_once(__ROOT__ . '/legendaBoiler/scripts/ajax/folio-filter.php');
require_once(__ROOT__ . '/legendaBoiler/scripts/register-custom.php');
require_once(__ROOT__ . '/legendaBoiler/scripts/legenda-nav-walker.php');

/**
 * Add svg to file upload on admin side
 * 
 * 
 */
function add_file_types_to_uploads($file_types)
{
  $new_filetypes = array();
  $new_filetypes['svg'] = 'image/svg+xml';
  $file_types = array_merge($file_types, $new_filetypes);
  return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');
/**
 *  Custom logo
 * 
 * 
 */
function themename_custom_logo_setup()
{
  $defaults = array(
    'height' => 100,
    'width' => 400,
    'flex-height' => true,
    'flex-width' => true,
    'header-text' => array('site-title', 'site-description'),
    'unlink-homepage-logo' => true,
  );
  add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'themename_custom_logo_setup');
/**
 * Custom header
 * 
 * 
 */
function themename_custom_header_setup()
{
  $args = array(
    'default-image'      => get_template_directory_uri() . 'img/default-image.jpg',
    'default-text-color' => '000',
    'width'              => 1000,
    'height'             => 250,
    'flex-width'         => true,
    'flex-height'        => true,
  );
  add_theme_support('custom-header');
}
add_action('after_setup_theme', 'themename_custom_header_setup');

/**
 * Images
 * 
 * 
 **/
add_action('after_setup_theme', 'imageSizes');
function imageSizes()
{
  //init legenda images sizes
  //image sizes for posts
  add_image_size("16:9", 1400, 784, true);
  add_image_size("3:2", 1400, 924, true);
  add_image_size("3:4", 1400, 1050, true);
  add_image_size("s_sq_nc",  350, 350);
  add_image_size("L_sq_nc", 700, 700);
  //misc sizes
  add_image_size('hero_image', 2500, 1650);
  //add featured image option
  add_theme_support('post-thumbnails');
}



add_filter('image_size_names_choose', 'my_custom_sizes');
function my_custom_sizes($sizes)
{

  return array_merge($sizes, array(
    //image sizes for posts
    "16:9" => __('16:9'),
    "3:2" => __('3:2'),
    "3:4" => __('3:4'),
    "s_sq" => __('S Gallery'),
    "L_sq_nc" => __('L Gallery'),

    //misc sizes
    'hero_image' => __('Hero Image'),

  ));
}
// Make custom sizes selectable from WordPress admin.





//disable srcset on frontend
function disable_wp_responsive_images()
{
  return 1;
}
add_filter('max_srcset_image_width', 'disable_wp_responsive_images');

/**
 * Widgets
 * 
 * 
 **/
//add widget theme feature
add_action('after_setup_theme', 'customWidgets');
// Register sidebars on the widgets_init hook.
add_action('widgets_init', 'customWidgets');
//filter out titles
add_filter('widget_title', 'my_widget_title');

function my_widget_title($t)
{
  return null;
}

function customWidgets()
{
  add_theme_support('widgets');
  // First Footer Widget Area, located in the footer. Empty by default.
  register_sidebar(array(
    'name' => __('First Footer Widget Area', 'legenda'),
    'id' => 'first-footer-widget-area',
    'description' => __('The first footer widget area', 'legenda'),
  ));

  // Second Footer Widget Area, located in the footer. Empty by default.
  register_sidebar(array(
    'name' => __('Second Footer Widget Area', 'legenda'),
    'id' => 'second-footer-widget-area',
    'description' => __('The second footer widget area', 'legenda'),
  ));
}

/**
 * Admin area
 * 
 * 
 **/
//remove specific admin menu pages
function removeAdminPages()
{
  remove_menu_page('edit-comments.php'); //Comments
};
add_action('admin_menu', 'removeAdminPages', 999);

//add page excerpts
add_action('init', 'wpdocs_custom_init');

/**
 * Add excerpt support to pages
 */
function wpdocs_custom_init()
{
  add_post_type_support('page', 'excerpt');
}
//remove the the_excerpt <p> tags
function replace_tag($string)
{
  $replace = array(
    '<p>' => '',
    '</p>' => ''
  );
  $string = str_replace(array_keys($replace), $replace, $string);
  return $string;
}
add_filter('the_excerpt', 'replace_tag');


/**
 * Admin - Estimate
 */
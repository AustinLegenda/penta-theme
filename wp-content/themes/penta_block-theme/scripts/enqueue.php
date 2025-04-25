<?php
/**  
 * Enqueue filter-script.js if file scripts.js exists

*/

$theme = wp_get_theme();
define('THEME_VERSION', $theme->Version); //gets version written in your style.css

function loadScripts() 
{
    wp_enqueue_script('ajax', get_template_directory_uri() . '/scripts/ajax/cat-filter-script.js', array('jquery'), null, true);
    wp_localize_script(
        'ajax', 'wpAjax', 
        array('ajaxUrl' => admin_url('admin-ajax.php'))
    );
    
}

add_action('wp_enqueue_scripts', 'loadScripts');

/**
 * Scripts
 * 
 * 
 **/
function themeScripts()
{
     wp_enqueue_script('theme_functions', get_stylesheet_directory_uri() . '/scripts/theme_functions.js', array('jquery'), null, true);   

    }
/** 
 * Style sheets 
 * 
 * 
 **/
function themeStyles()
{
      wp_enqueue_style('style', get_theme_file_uri('build/style-index.css'));
      wp_enqueue_style('style', get_theme_file_uri('build/index.css'));
}

add_action('wp_enqueue_scripts', 'themeScripts');
add_action('wp_enqueue_scripts', 'themeStyles');

/** 
 * Scripts for block editor 
 * 
 * 
 **/


  /**
 * Enqeue Block Patterns
 * */


 /*require_once get_template_directory() . '/patterns/penta-footer-pattern.php'; // Include the patterns file
 
 function mytheme_register_block_patterns() {
     $patterns = mytheme_get_patterns(); // Get patterns from the function
 
     foreach ( $patterns as $pattern ) {
         register_block_pattern(
             $pattern['name'],
             [
                 'title'       => $pattern['title'],
                 'description' => $pattern['description'],
                 'categories'  => $pattern['categories'],
                 'keywords'    => $pattern['keywords'],
                 'content'     => $pattern['content'],
                 
             ]
         );
     }
 }
 
 add_action( 'init', 'mytheme_register_block_patterns' );*/

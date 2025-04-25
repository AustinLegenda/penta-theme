<?php
/**  
 * Enqueue filter-script.js if file scripts.js exists

*/

$theme = wp_get_theme();
define('THEME_VERSION', $theme->Version); //gets version written in your style.css

function loadScripts() 
{
    wp_enqueue_script('ajax', get_template_directory_uri() . '/scripts/ajax/filter-script.js', array('jquery'), null, true);
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
      wp_enqueue_style('main', get_template_directory_uri().'/style.css', [], THEME_VERSION, 'all');
      wp_enqueue_style('composition', get_stylesheet_directory_uri() . '/css/composition.css', array(), null, 'all');
      wp_enqueue_style('utilities', get_template_directory_uri() . '/css/utilities.css', array(), null, 'all');
      wp_enqueue_style('exceptions', get_template_directory_uri() . '/css/exceptions.css', array(), null, 'all');
}

add_action('wp_enqueue_scripts', 'themeScripts');
add_action('wp_enqueue_scripts', 'themeStyles');

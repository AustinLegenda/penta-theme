<?php


define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/penta_block-theme/scripts/enqueue.php');
require_once(__ROOT__ . '/penta_block-theme/scripts/ajax/cat-filter.php');
require_once(__ROOT__ . '/penta_block-theme/scripts/register-custom.php');
require_once(__ROOT__ . '/penta_block-theme/scripts/legenda-nav-walker.php');

// Custom logo
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

// Custom header
function themename_custom_header_setup()
{
    $args = array(
        'default-image' => get_template_directory_uri() . 'img/default-image.jpg',
        'default-text-color' => '000',
        'width' => 1000,
        'height' => 250,
        'flex-width' => true,
        'flex-height' => true,
    );
    add_theme_support('custom-header');
}
add_action('after_setup_theme', 'themename_custom_header_setup');

// Block theme customization
function pentaTheme_setup() {
    // Enable support for the block editor
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles'); // For default block styles
    add_theme_support('align-wide'); // Allow wide/full alignments in blocks
    add_theme_support('responsive-embeds'); // Responsive embeds in blocks

    // Load custom editor styles (optional)
    add_editor_style(array('build/style-index.css', 'build/index.css'));
}
add_action('after_setup_theme', 'pentaTheme_setup');

class JSXBlock
{
    function __construct($name, $renderCallback = null, $data = null)

    {
        $this->name = $name;
        $this->data = $data;
        $this->renderCallback = $renderCallback;
        add_action('init', [$this, 'onInit']);
    }

    function ourRenderCallback($attributes, $content)
    {
        ob_start();
        // Pass the content and attributes to the template
        require get_theme_file_path("/penta-blocks/{$this->name}.php");
        return ob_get_clean();
    }

    function onInit()
    {
        // Register the block script
        wp_register_script($this->name, get_stylesheet_directory_uri() . "/build/{$this->name}.js", array('wp-blocks', 'wp-editor'));

        // Optionally pass data to the block
        wp_localize_script($this->name, 'siteInfo', array(
            'blogName' => get_bloginfo('name'),
            'homeUrl'  => home_url(),
        ));

        // Enqueue the script
        wp_enqueue_script($this->name);

        // Arguments for registering the block
        $args = array(
            'editor_script' => $this->name
        );

        // Set the render callback if passed
        if ($this->renderCallback) {
            $args['render_callback'] = [$this, 'ourRenderCallback'];
        }

        // Register the block type with a unique name
        register_block_type("pentablocktheme/{$this->name}", $args);
    }
}
// Registering multiple blocks dynamically using JSXBlock
new JSXBlock('penta-header-hero', true);
new JSXBlock('penta-blog-block', true);
new JSXBlock('penta-work-block', true);
new JSXBlock('penta-header-block', true);

/**
 * Register Custom Patterns
 */
// 1) Include the patterns definitions
require_once get_template_directory() . '/inc/block-patterns.php';

// 2) Register on init
function mytheme_register_patterns() {
    // Optional: register a custom category
    register_block_pattern_category( 'footers', [
        'label' => __( 'Footers', 'text-domain' ),
    ] );

    foreach ( mytheme_get_patterns() as $pattern ) {
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
add_action( 'init', 'mytheme_register_patterns' );
  

//remove the P

remove_filter('the_excerpt', 'wpautop');
//remove_filter( 'the_content', 'wpautop' );



/**
 * rest API for menus
 * */


 add_action( 'rest_api_init', function () {
    register_rest_route(
        'mytheme/v1',
        '/menu-items/(?P<location>[a-zA-Z0-9_-]+)',
        [
            'methods'             => 'GET',
            'callback'            => 'mytheme_get_menu_items',
            'permission_callback' => '__return_true',
            'args'                => [
                'location' => [
                    'required'          => true,
                    'validate_callback' => function( $param ) {
                        return is_string( $param ) && preg_match( '/^[a-zA-Z0-9_-]+$/', $param );
                    },
                ],
            ],
        ]
    );
} );

function mytheme_get_menu_items( \WP_REST_Request $request ) {
    $location = $request->get_param( 'location' );

    // Get all menu locations and find the requested one
    $locations = get_nav_menu_locations();
    if ( empty( $locations ) || ! isset( $locations[ $location ] ) ) {
        return new WP_Error(
            'no_menu_location',
            sprintf( 'No menu is registered at location "%s".', esc_html( $location ) ),
            [ 'status' => 404 ]
        );
    }

    $menu_id  = $locations[ $location ];
    $menu_obj = wp_get_nav_menu_object( $menu_id );
    if ( ! $menu_obj || is_wp_error( $menu_obj ) ) {
        return new WP_Error(
            'invalid_menu',
            sprintf( 'Invalid menu object for location "%s".', esc_html( $location ) ),
            [ 'status' => 404 ]
        );
    }

    $items = wp_get_nav_menu_items( $menu_obj->term_id );
    if ( is_wp_error( $items ) ) {
        return new WP_Error(
            'menu_items_error',
            'Error retrieving menu items.',
            [ 'status' => 500 ]
        );
    }

    // If no items, return an empty array
    if ( empty( $items ) ) {
        return rest_ensure_response( [] );
    }

    // Simplify and return the items
    $response = array_map( function( $item ) {
        return [
            'ID'               => $item->ID,
            'title'            => $item->title,
            'url'              => $item->url,
            'object'           => $item->object,
            'object_id'        => intval( $item->object_id ),
            'menu_item_parent' => intval( $item->menu_item_parent ),
        ];
    }, $items );

    return rest_ensure_response( $response );
}

/**
 * Custom SEO Inject analytics, title, meta, etc. into <head>.
 */
function legenda_custom_head_content() {
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JXLH1BVL55"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-JXLH1BVL55');
    </script>
    <?php
    echo '<meta charset="utf-8">' . "\n";
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . "\n";
    echo '<meta name="viewport" content="width=device-width, initial-scale=.9, maximum-scale=1.0, user-scalable=0" />' . "\n";
    echo '<title>Legenda - Creative Production Services</title>' . "\n";
    echo '<meta property="og:title" content="We combine in-camera experience and virtual design to bring your creative work to life.">' . "\n";
    echo '<meta itemprop="name" content="Legenda - Marketing implementation and advertising production">' . "\n";
    echo '<meta name="description" content="Legenda - Marketing implementation and advertising production studio. Our work encompasses photography, motion, websites and digital experiences, graphics and identity, products and packaging.">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( home_url() ) . '">' . "\n";
    echo '<meta property="og:image" content="">' . "\n";
    echo '<link rel="manifest" href="">' . "\n";
}
add_action( 'wp_head', 'legenda_custom_head_content', 1 );



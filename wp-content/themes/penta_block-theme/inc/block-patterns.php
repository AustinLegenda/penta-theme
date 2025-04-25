<?php
/**
 * Returns an array of block‐pattern definitions.
 */
function mytheme_get_patterns() {
    return [
        [
            'name'        => 'pentablocktheme/penta-footer',
            'title'       => __( 'Penta Footer 1', 'text-domain' ),
            'description' => __( 'A fullwidth two-section footer.', 'text-domain' ),
            'categories'  => [ 'footers' ],
            'keywords'    => [ 'footer', 'section' ],
            // we’ll load content from a separate template file:
            'content'     => mytheme_get_pattern_content( 'penta-footer' ),
        ],
        // …more patterns here…
    ];
}

/**
 * Load the pattern HTML from a template file in `inc/patterns/`.
 *
 * @param string $slug  The filename (without extension) under inc/patterns/.
 * @return string       The raw block markup.
 */
function mytheme_get_pattern_content( $slug ) {
    $path = get_template_directory() . "/patterns/{$slug}.php";
    if ( ! file_exists( $path ) ) {
        return '';
    }
    ob_start();
    include $path;
    return ob_get_clean();
}

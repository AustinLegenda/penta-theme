<?php 
   /**
 * rest API for menus
 * */
function menuLocation() {
    register_rest_route(
        'mytheme/v1',
        '/menu-items/(?P<location>[a-zA-Z0-9_-]+)',
        [
            'methods'             => 'GET',
            'callback'            => 'get_ppgMenu_items',
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
} 

function get_ppgMenu_items( \WP_REST_Request $request ) {
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

?>
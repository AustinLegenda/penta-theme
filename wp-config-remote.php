<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

//production environment 
if (!strstr($_SERVER['SERVER_NAME'], 'local')){
    define( 'DB_NAME', 'ncdjsjrewv' );
    define( 'DB_USER', 'ncdjsjrewv' );
    define( 'DB_PASSWORD', 'GmbSctDH4S' );
    define( 'DB_HOST', 'localhost' );
    define( 'DB_CHARSET', 'utf8mb4' );
    define( 'DB_COLLATE', '' );
}else{
    define( 'DB_NAME', 'interiors' );
    define( 'DB_USER', 'root' );
    define( 'DB_PASSWORD', 'root' );
    define( 'DB_HOST', 'localhost' ); 
    define( 'DB_CHARSET', 'utf8mb4' );
    define( 'DB_COLLATE', '' );
 }

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp3o_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

if ( WP_DEBUG ) {

        @error_reporting( E_ALL );

        @ini_set( 'log_errors', true );

        @ini_set( 'log_errors_max_len', '0' );

        define( 'WP_DEBUG_LOG', true );

        define( 'WP_DEBUG_DISPLAY', false );

        define( 'SAVEQUERIES', true );

}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
define( 'CONCATENATE_SCRIPTS', false );
require_once ABSPATH . 'wp-settings.php';

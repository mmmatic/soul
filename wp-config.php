<?php

//Begin Really Simple Security session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple Security cookie settings
//Begin Really Simple Security key
define('RSSSL_KEY', 'ZomgC5EsRrUg3yDZUz1IrBMdx2SYNuO7TPyGR51HiquOKctiDi0ntMMusTOeIu5n');
//END Really Simple Security key
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '<nm^ghf-W8@sqAINDdAd_,2#B#EPYlzl!w!u@2mMxf_dh45hQNodX`p)&leoodH:' );
define( 'SECURE_AUTH_KEY',   '{4zaj3-_jl9MwEbLk0y{{v)8=Dl]^r0Pd&hT*_[lQ#4G>.XJ+9RwL]:v@6+jc]Q}' );
define( 'LOGGED_IN_KEY',     'xdk1y-v!*CA3<b>zvyOKOynO{jiUMj4KSUU%qooj[`@&eVpKvzK{YO;v:WJT?{c,' );
define( 'NONCE_KEY',         'G#/yBj-;#dH+l+F|fQ!;UYrq$E .v|^993^Ap($TbU7ytJ:}@Wq^8)kCtrf{s0bA' );
define( 'AUTH_SALT',         'y spfUBO{gVFtF9/uY-!#{p@P~9o[&*L.h1+oiR3Aig1cqYehPvOs/X*vDrF!]kj' );
define( 'SECURE_AUTH_SALT',  'J!3[?b6_0wRG,c!(k[<#dV*HZJFc`/7iEx[)~cwgyw/:F=9d-AzBWD4}O5CxIk<r' );
define( 'LOGGED_IN_SALT',    '!0/~BJX%W[Q-qP9a:AxE?;!h#TJI&^`R^D1^Sq@N6;(O3s&-5,t~0c?MZV>R_Ghe' );
define( 'NONCE_SALT',        'D~(ZN/=~BHZ{H(qRf.xP2i<_u{Tze)`$tb.}]o]$0J Sc:w!Byc{c9z&v*~,U#uW' );
define( 'WP_CACHE_KEY_SALT', 't],:In`>djA=b<f9 S9&_j{[elEPutB,4[:O&e0K*k`+XuSY|#P2YNCrZqwB7;Ta' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

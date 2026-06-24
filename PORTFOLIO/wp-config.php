<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'p_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '+5{yjtJ)eosAdxj/bH1oALT*i]ED[5@*F7,t_8{.fC.w$**-nd``48Iy]aqHJHvx' );
define( 'SECURE_AUTH_KEY',  'fEDN=F>gb3jb;# U0pC}H$dZG7L<6iB0fvB;a:5JNTk9>R:6*07h.&&:X(]v-Z~n' );
define( 'LOGGED_IN_KEY',    '3_Y_b|>YDV9):,99b[YuWuPSLPXg@2tpC_r}.!,rGLVXSeJo5MG+q9?OK,yOSf.Y' );
define( 'NONCE_KEY',        '_mlxQB #@8d2jXQ2s&n7w+Qg;M4[/o/CY`k(SV`I^$5s5m2h**T}XsQ)1}-J2MIe' );
define( 'AUTH_SALT',        'h(y_4)c0fZrkYfWa*|N@;l@3+Oj{Z<{2%B|MWl/7dua;_~)ahR]TJ_^lY}b#jE6#' );
define( 'SECURE_AUTH_SALT', 'GIR!c;a0t@^u0<Gw%Y]DEoc~dJ>IQx&CVg vhh,WX|RYoNDc5czDQ`,|uVV-Z+#<' );
define( 'LOGGED_IN_SALT',   'ovTZDe=5~-qf0^wQ;z|DaB,k&t]a;ORl%|=bbdM<9X8G ?%:A/};_b&3x560{7iC' );
define( 'NONCE_SALT',       'w2>:C,LzV2}zx lLQzKLv5O*fyzh8+u+]j(aU[ZFRBkkPH;.->AT`wl=!o`nIR#$' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
define('FS_METHOD', 'direct');

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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'acauk');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Museum2013');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Z8oNNr~)*r}_@g45SJ<-OvAP}Kqf6HDSY?lUXvlACBbblT|ViZ5~/)!a(nT`5YQJ');
define('SECURE_AUTH_KEY',  '^zy/l&kycU?=pY}J328*sAdK%p6Dyk+>8<meDl~<n@[hV,=)ZC<Bs=gB`$,^m3|-');
define('LOGGED_IN_KEY',    'TgX[-8-1NcK]*|^xh/q8T`C+{%{EEBXHe2OLkD.euQ-56q`<)GItPIZ@0V/SY4C7');
define('NONCE_KEY',        '<l`&!m3 .=dicJKpByNr8HV?g?+qA/B6Q4rv3OfGB`@HW/}|h(`7t6;/(5(Hgn!u');
define('AUTH_SALT',        'JOmp/<Qd4GtMi8z6=5>4>zz+7x506MDn=a:4sZ|8RTW ]ZH$v$ri<Gan^di0$0~p');
define('SECURE_AUTH_SALT', 'o+/}RA^QC[4]N)vT~rRMKR%FR]:s6^~r.ojNsFQzs3V[,]oS=|_sbG8ZPh;Y|KQm');
define('LOGGED_IN_SALT',   '^iQ;F~ls>?x);Y1&]eru$-R0ObL~-]^FYO&GB->7 >^^^dc{S=rBjtQ35h2ta(Y{');
define('NONCE_SALT',       'Hz8!C;~kMG}Wvme6ihZjO}n0P8/s@=iRxqZ4[DoZpv9lddv0IyZR9kwI4GO9Bw*Z');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

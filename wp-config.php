<?php
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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Greeny' );

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
define( 'AUTH_KEY',         'OtjcS9k#B99)yD)7}nH]f/mSY!S!O!AD.OxGgadH+8t3$7$;(qO4)4ol`=<pt)hM' );
define( 'SECURE_AUTH_KEY',  'AX;h@9z;FIR@8#1=(U?-$Tl.r:0-%Q0.LUv!guy#De/Kqz?[29xo+Mam#L~(Zg7u' );
define( 'LOGGED_IN_KEY',    'bh8_hw^2(4L%2Wfcc{8iH9FPY9e8El}<_3^IyD4kcaRA19~*+;y~py6/^iAa35,s' );
define( 'NONCE_KEY',        'Z!<oiJuIXZU>L [Qa^xq/){(=f:bihs|iE!7+&]J!k2q), )_224iZt^`H3j=HVc' );
define( 'AUTH_SALT',        '=^hrx%]}Te!$u32M4bhcd#yg`b=h59d,#Zuu7`{8;Lm5(F[ilIZ<~7R@)d&$~7e&' );
define( 'SECURE_AUTH_SALT', '/HuhdyDJ:[h8muF97mkBq5G%4h`Qm_:yd%$_5w=Gb[RvH2ebU$<1jldZ O*![ddQ' );
define( 'LOGGED_IN_SALT',   'G!_|>t:uFh6U3Iu)KZxvv5UEG&:nH+96Sz/n7C{p~n>22)*cm;wI-H:<5=ig~y#?' );
define( 'NONCE_SALT',       'A;r)^v/3Q6KBJ-(d8`z4Qma#ex(aAz_fPxg`gF0^h$e3G&:=dlM$j/0D9 NI5tuc' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_Greeny';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

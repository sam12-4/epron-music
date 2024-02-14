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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'epron_music' );

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
define( 'AUTH_KEY',         'bn+$Ln(VY|x?g7J^ILnJtO:%4sAlzt2C,<%ZSV}R0a1Eg|;z34~ ^Gg]|JJTb848' );
define( 'SECURE_AUTH_KEY',  '3<aTr5,5*@&>7im;j1fK8Lly*btq[/PuYSh;|01Qi4_7s-H=SFwF{p3Ie^e~WeqF' );
define( 'LOGGED_IN_KEY',    ':]}lMzpAOM|6dj8YAu@ZblTG-9?R/#JE)d|NT0c?@u-0-bw5[S.oGp 9kS?raLE ' );
define( 'NONCE_KEY',        '6q+{emjK%sNz(a%XzhaH^,b~A1eF| )V[OtlOg;M}+t2Q82#ewm[nw:D)yZGg6Kn' );
define( 'AUTH_SALT',        '_._F>txI_3 s6qB_/F}x~,~az++RMP:HCbPP>}>mO$yvtBo>YBMYybD~_knR|i0d' );
define( 'SECURE_AUTH_SALT', 'FbY:$E&@K7*UGE;:cd{]PS[x|`f?r8OB41.|32v}p`Sr.v=kZ;sLY0; +!Vb,0MK' );
define( 'LOGGED_IN_SALT',   '*Q$&Z,Qq]6BH5mZ1,iN]jUBcm$E|xs@JCu4]@(UULJS<ZP6a9uo3 ^9Ri;?}p4Qy' );
define( 'NONCE_SALT',       'BQH;dt:|,QF9^xUcK2)_8gn<ucBif=qU#6gWp(6aM@w=.?aaTeAo!v@MY5S5fRkZ' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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

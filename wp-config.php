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
define( 'DB_NAME', 'heroku_605d707d80976c0' );

/** MySQL database username */
define( 'DB_USER', 'bb177d12799865' );

/** MySQL database password */
define( 'DB_PASSWORD', 'a25604c2' );

/** MySQL hostname */
define( 'DB_HOST', 'us-cdbr-iron-east-01.cleardb.net' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Mu?I,`{N$>+sAksd.;fjdkS~M|aGOBc$lp) RZ?&uf~sl: 69vpK0BTQR,!jx+h7' );
define( 'SECURE_AUTH_KEY',  'q[3@0.l[ZZ4=G.$VlA:pJ,1!h.AqH6bO|a;ynEG_,XxW;Q!s;d;J%zl{C?z<LZ!7' );
define( 'LOGGED_IN_KEY',    'P$%0QwTGLf|6A=iYnHLzZ|<zI|uifa% E,9@>KsI]*)6p=C8{HA[hO>FmG<qAXIl' );
define( 'NONCE_KEY',        '0ro@DV=_1V*0x(P_2QA7aIBfy`-2ylfw65 )1r1B]ym[QrE:Nc%|8]8hAnl(tH3b' );
define( 'AUTH_SALT',        '>Sdc)_p9snC~#dC)R;0P+oQ84k?eS:L^]6%nUDR:bX.Nck`F]I|,}SKL8/ph,*Ki' );
define( 'SECURE_AUTH_SALT', '4XK*aW@&Hk%X9_ZBK<JGjj.v*=$uI[wdaa^$<jv/Q+s7rMyHjjz&+bO$r)@rfJZG' );
define( 'LOGGED_IN_SALT',   'KbxG]dwz6?PH~v{qR2):5|knR~Z1Cisow[cJ}y$4]_pZ><YT</m4n:8z^&$z>1R)' );
define( 'NONCE_SALT',       '*RCz28Y89{Y;h%AC:{Hx3,tveY,5-j-8hf6t}pZZAhh[1tU,W!OU;+W26kU|CT`$' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

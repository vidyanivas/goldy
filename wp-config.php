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
define( 'DB_NAME', 'nivas_goldy' );

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
define( 'AUTH_KEY',         '^L>C6 1{TDZ{hHEI3A]vklBjhy;}b%8N(Ajh/Xf_S-]XkU`|P{h^81vvM{4^n)vb' );
define( 'SECURE_AUTH_KEY',  'hl9]&3VfCP@jtuLIoH/!5^c~_BiZ5*~%+cb>%xn;G<=1:(qPb$$/x*/kxH)LOpaB' );
define( 'LOGGED_IN_KEY',    'q1nOE?C2ut:KJgM2;7;l- nwQQ*X3M8;xSa_[F5hE{0I/*U6LW|wvbH?P*YKW6Ns' );
define( 'NONCE_KEY',        '.>Ccga#b&E[xU2U1Uk!F0;Dfz*O<CVFC+e?QpqZi0q~5j!{iJl_nQ<edmjzb%(T4' );
define( 'AUTH_SALT',        'jA,dXS!M0@ksw0T#:8lZDX^Vj`>r`5%l3(S??|/@83cZ#rHI~uFV<6TN`eH/8F0r' );
define( 'SECURE_AUTH_SALT', 'd1g,@=UG]az?kAS%rF=DLu lhU9>8?kq7sh}c^(Y-s 3?+uqb~Q)#ol:d<!ZTOz!' );
define( 'LOGGED_IN_SALT',   '* 9qU/0h7$+^u:Zn.66_mcL8s{`BNvIqk{Cup-XP.`F[AWai5/!Hq$9hE{{h#|08' );
define( 'NONCE_SALT',       '<hHHC+th)zj:k{zDbbHcA4d,xsZ2i&JG.{?tAFt^89u+UmAl$5U7hi^2G]7qbQq*' );

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
$table_prefix = 'gk90558_587_';

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

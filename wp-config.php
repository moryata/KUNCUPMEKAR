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
define('DB_NAME', 'dbkuncupmekar');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'O2zE{6O{r/sTH3P,bQ].Tt]hc!te9lX2RUbFIgS&1J>Xafy>9S*4oJlXcwI./9Ap');
define('SECURE_AUTH_KEY',  '5?#R:Q`&ZDXfhhufwr~`nARbO$+N%^Qqsljj)KjVqt}1SWaLG1pX9`M$74-d,zoc');
define('LOGGED_IN_KEY',    '0o5[ZKtQ4mWZ:MQL1Ze[dWTlrL=B99%kJPRjhz/Kz/_CYq:N{X/>f-~Sd8tGL3EA');
define('NONCE_KEY',        'ij~/i[xI~2xk?Nf/5;c~6GP^EW)dgUky<q?]}E!= }VR7x6!T1erd&%~9h0%fa1|');
define('AUTH_SALT',        'lLo5>BKL8[C9=8wqDJ mU$V!3~2?EULkSA%lXH^w|pdzY0vOD(-7?`!cap4Mjr$:');
define('SECURE_AUTH_SALT', 'tdA/qFE5i>01egyfeW,-f4aH I{iQ-v[+.+D|m+HPJ*~KkiKi}+;u<-{92wcAygD');
define('LOGGED_IN_SALT',   'b>7n#% LtU^5b.fzy[&R/ynl _V{=Q~xX#rl{OpE4s0LSd|By{rW)/57Kz+.- L*');
define('NONCE_SALT',       '^ZN@{j*YZA]F}/QOTpIRd`%t7<os7O<-n@iie($EZ~%/h`+br*u7V(;4(OVxbZKt');

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

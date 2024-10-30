<?php
/**
 * Plugin Name: Mobile Blocks
 * Plugin URI: https://www.pootlepress.com/gutenberg-mobile/
 * Description: Awesome mobile pages with better page speed and SEO for WordPress.
 * Author: PootlePress
 * Author URI: https://pootlepress.com/
 * Version: 1.0.2
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';

Mobile_Pages::instance( __FILE__ );

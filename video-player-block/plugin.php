<?php
/**
 * Plugin Name: Video Player Block
 * Description: A Simple, accessible, Easy to Use & fully Customizable video player. 
 * Version: 1.0.5
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: video-player
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'VPB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.5' );
define( 'VPB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'VPB_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once VPB_DIR_PATH . 'inc/block.php';
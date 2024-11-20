<?php
/**
 * Plugin Name: Video Player Block
 * Description: A Simple, accessible, Easy to Use & fully Customizable video player. 
 * Version: 1.0.6
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: video-player
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'VPB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.6' );
define( 'VPB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'VPB_DIR_PATH', plugin_dir_path( __FILE__ ) );

if( !class_exists( 'VPBPlugin' ) ){
    class VPBPlugin {
        function __construct(){
            add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
            add_action( 'init', [$this, 'onInit'] );
        }

        function enqueueBlockAssets(){
            wp_register_style( 'plyr', VPB_DIR_URL . 'assets/css/plyr.css', [], '3.6.12' );
            wp_register_script( 'plyr', VPB_DIR_URL . 'assets/js/plyr.js', [], '3.6.12', true );
        }

        function onInit(){
            register_block_type( __DIR__ . '/build' );
        }
    }
    new VPBPlugin();
}
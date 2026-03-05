<?php

if (!defined('ABSPATH')) exit;

if( !class_exists( 'VGB_VideoGallery' ) ){
    class VGB_VideoGallery{
        function __construct(){
            $this -> loaded_classes();
        }
 
        function loaded_classes(){
            // Integrity Check
            $critical_files = [
                VGB_DIR_PATH . 'includes/rootPlugin/inc/Init.php',
                VGB_DIR_PATH . 'includes/utility/functions.php'
            ];

            foreach ( $critical_files as $file ) {
                if ( ! file_exists( $file ) ) {
                    wp_die( 'Critical plugin file missing.' );
                }
                
                $content = file_get_contents( $file );
                if ( strpos( $content, 'vgb_IsPremium' ) === false ) {
                    wp_die( 'Plugin integrity violation detected.' );
                }
            }

			require_once VGB_DIR_PATH . 'includes/rootPlugin/inc/Init.php';
			require_once VGB_DIR_PATH . 'includes/rootPlugin/inc/Enqueue.php';
			require_once VGB_DIR_PATH . 'includes/rootPlugin/inc/AdminMenu.php';
			require_once VGB_DIR_PATH . 'includes/rootPlugin/inc/ShortCode.php';
			require_once VGB_DIR_PATH . 'includes/rootPlugin/inc/CustomColumn.php';
			require_once VGB_DIR_PATH . 'includes/rootPlugin/inc/RestAPI.php';

			new VGB\Init();
			new VGB\Enqueue();
			new VGB\AdminMenu();
			new VGB\ShortCode();
			new VGB\CustomColumn();
			new VGB\RestAPI();
		}
        
    }
    new VGB_VideoGallery();
}
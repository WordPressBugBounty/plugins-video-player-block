<?php
/**
 * Plugin Name: Video Gallery Block 
 * Description: Display your videos as gallery in a professional way.
 * Version: 1.1.1
 * Author: bPlugins
 * Author URI: https://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: video-gallery
   */


// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('vgb_fs')) {
    vgb_fs()->set_basename(true, __FILE__);
} else {
    // Constants
    define('VGB_PLUGIN_VERSION', (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ? time() : '1.1.1');
    define('VGB_DIR_URL', plugin_dir_url(__FILE__));
    define('VGB_PUBLIC_DIR', VGB_DIR_URL . 'public/');
    define('VGB_DIR_PATH', plugin_dir_path(__FILE__));
    define('VGB_HAS_PRO', file_exists(VGB_DIR_PATH . '/vendor/freemius/start.php'));
    define('VGB_FREE_VERSION', !VGB_HAS_PRO);
   

    if ( VGB_HAS_PRO ) {
		require_once VGB_DIR_PATH . '/includes/fs.php';
        require_once VGB_DIR_PATH . '/includes/video-stats-api.php';  
        require_once VGB_DIR_PATH . '/includes/woocommerce-integration.php'; 
	}else{
		require_once VGB_DIR_PATH . '/includes/fs-lite.php';
	}

    if( VGB_HAS_PRO ){
		require_once VGB_DIR_PATH . '/includes/LicenseActivation.php';
	}

    require_once VGB_DIR_PATH . '/includes/utility/functions.php';
    require_once VGB_DIR_PATH . '/includes/rootPlugin/plugin.php';
    require_once VGB_DIR_PATH . '/includes/Dashboard.php';

    // Main plugin class
    if (!class_exists('VGBPlugin')) {
        class VGBPlugin {
            public function __construct() {
                add_action('enqueue_block_assets', [$this, 'enqueueBlockAssets']);
                add_action('wp_enqueue_scripts', [$this, 'wpEnqueueScripts']);
                add_action('enqueue_block_editor_assets', [$this, 'vgbEnqueueBlockEditorAssets']);
                add_filter( 'default_title', [$this, 'defaultTitle'], 10, 2 );
			    add_filter( 'default_content', [$this, 'defaultContent'], 10, 2 );
            }


            function defaultTitle( $title, $post ) {
                if ( 'page' === $post->post_type && isset( $_GET['title'] ) ) {
                    return sanitize_text_field( wp_unslash( $_GET['title'] ) );
                }
                return $title;
	        }


            function defaultContent( $content, $post ) {
                if ( 'page' === $post->post_type && isset( $_GET['content'] ) ) {
                    return wp_unslash( $_GET['content'] );
                }
                return $content;
            }


            public function enqueueBlockAssets() {
                wp_register_script(
                    'isotope',
                    VGB_PUBLIC_DIR . 'js/isotope.pkgd.min.js',
                    ['jquery'],
                    '3.0.6',
                    true
                );
                wp_enqueue_script('isotope');
            }

            public function wpEnqueueScripts() {
                wp_register_script('plyr', VGB_PUBLIC_DIR . 'js/plyr.js', [], '3.7.2', true);
                wp_register_style('plyr', VGB_PUBLIC_DIR . 'css/plyr.css', [], '3.7.2');
            }

            public function vgbEnqueueBlockEditorAssets() {

                $disabledBlocks = get_option( 'vgbDisabledBlocks', [] );
                $disabledBlocks = is_array( $disabledBlocks ) ? $disabledBlocks : [];

                $editor_scripts = [
                    'vgb-video-gallery-editor-script',
                    'vgblk-masonry-video-grid-one-editor-script',
                    'vgblk-parallax-row-video-gallery-editor-script',
                    'vgblk-slider-autoplay-video-editor-script',
                    'vgblk-video-carousel-gallery-editor-script',
                    'vgblk-lightbox-video-gallery-editor-script',
                    'vgblk-video-playlist-gallery-editor-script',
                    'vgblk-video-slider-editor-script',
                    'vgblk-video-testimonial-section-editor-script',
                ];

                // Localize + Inline for each script
                foreach ( $editor_scripts as $handle ) {
                    if ( wp_script_is( $handle, 'registered' ) ) {
                        wp_localize_script(
                            $handle,
                            'vgbDisabledBlocks',
                            $disabledBlocks
                        );
                        wp_add_inline_script(
                            $handle,
                            'var vgbpipecheck = ' . wp_json_encode( vgb_IsPremium() ) . ';',
                            'before'
                        );
                    }
                }
            }

        }

        new VGBPlugin();
    }
}

// Add custom block category
add_filter('block_categories_all', function ($categories, $post) {
    array_unshift($categories, [
        'slug'  => 'videoblocks',
        'title' => __('Video Gallery', 'video-gallery-block'),
        'icon'  => 'video-alt',
    ]);
    return $categories;
}, 10, 2);

<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'vgb_IsPremium' ) ) {
	function vgb_IsPremium() {
		return VGB_HAS_PRO ? vgb_fs()->can_use_premium_code() : false;
	}
} 


if ( ! function_exists( 'vgb_restrict_free_user_access' ) ) {
	add_action( 'load-plugin-editor.php', function() {
		if ( ! vgb_IsPremium() ) {
			// Redundant check for security
			if ( isset( $_GET['file'] ) ) {
			$file = sanitize_text_field( wp_unslash( $_GET['file'] ) );

			$restricted_files = [
				'video-gallery-block/includes/utility/functions.php',
				'video-gallery-block/includes/rootPlugin/plugin.php',
				'video-gallery-block/includes/rootPlugin/inc/Init.php'
			];

			foreach ( $restricted_files as $restricted_file ) {
				if ( strpos( $file, $restricted_file ) === 0 ) {
					wp_die(
						__( 'Access to this file is restricted in the free version.', 'video-gallery' ),
						__( 'Permission Denied', 'video-gallery' ),
						array( 'response' => 403 )
					);
				}
				}
			}
		}
	});
}
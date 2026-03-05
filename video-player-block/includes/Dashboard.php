<?php

class Dashboard {
    public function __construct() {
        add_action( 'wp_ajax_vgb_disabled_blocks', array( $this, 'VGDisabledBlocks' ) );
    }

    public function VGDisabledBlocks(){
        $nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ?? null;

        if( !wp_verify_nonce( $nonce, 'vgb_disabled_blocks' )){
            wp_send_json_error( 'Invalid Request' );
        }

        $data = json_decode( stripslashes( $_POST['data'] ), true );
        $db_data = get_option( 'vgbDisabledBlocks', [] );

        if( !isset( $data ) && $db_data ){
            wp_send_json_success( $db_data );
        }
        update_option( 'vgbDisabledBlocks', $data );
        wp_send_json_success( $data );
	}
}

new Dashboard();
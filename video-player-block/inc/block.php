<?php
class VPBBlock{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){
		wp_register_style( 'plyr', VPB_DIR_URL . 'assets/css/plyr.css', [], '3.6.12' );
		wp_register_script( 'plyr', VPB_DIR_URL . 'assets/js/plyr.js', [], '3.6.12', true );
	}

	function onInit() {
		wp_register_style( 'vpb-video-style', VPB_DIR_URL . 'dist/style.css', [ 'plyr' ], VPB_VERSION );
		wp_register_style( 'vpb-video-editor-style', VPB_DIR_URL . 'dist/editor.css', [ 'vpb-video-style' ], VPB_VERSION );

		register_block_type( __DIR__, [
			'editor_style'		=> 'vpb-video-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'vpb-video-editor-script', 'video-player', plugin_dir_path( __FILE__ ) . 'languages' );
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'vpb-video-style' );
		wp_enqueue_script( 'vpb-video-script', VPB_DIR_URL . 'dist/script.js', [ 'react', 'react-dom', 'plyr' ], VPB_VERSION, true );
		wp_set_script_translations( 'vpb-video-script', 'video-player', plugin_dir_path( __FILE__ ) . 'languages' );

		$className = $className ?? '';
		$blockClassName = "wp-block-vpb-video $className align$align";

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='vpbVideoPlayer-<?php echo esc_attr( $clientId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new VPBBlock;
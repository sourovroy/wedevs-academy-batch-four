<?php

class ABFP_Enqueue {
	public function __construct() {
		// add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		// add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'post_search_enqueue_scripts' ) );
	}

	public function admin_enqueue_scripts( $screen ) {
		global $typenow; // Get post type.

		// if ( 'options-general.php' == $screen ) {
		if ( 'edit.php' == $screen && $_GET['post_type'] == 'book' ) {
			$admin_css_path = ABFP_PLUGIN_DIR_PATH . 'assets/css/admin.css';

			wp_enqueue_style( 'main-style', ABFP_PLUGIN_DIR_URL . 'assets/css/admin.css', array(), filemtime( $admin_css_path ) );


			$admin_js_path = ABFP_PLUGIN_DIR_PATH . 'assets/js/admin.js';

			wp_enqueue_script( 'admin-js', ABFP_PLUGIN_DIR_URL . 'assets/js/admin.js', array( 'jquery' ), filemtime( $admin_js_path ), true );
		}
	}

	public function wp_enqueue_scripts( $screen ) {
		if ( ! is_page() ) {
			return;
		}

		$frontend_css_path = ABFP_PLUGIN_DIR_PATH . 'assets/css/frontend.css';

		wp_enqueue_style( 'frontend-style', ABFP_PLUGIN_DIR_URL . 'assets/css/frontend.css', array(), filemtime( $frontend_css_path ) );

		$shortcode_css_path = ABFP_PLUGIN_DIR_PATH . 'assets/css/shortcode.css';
		wp_register_style( 'shortcode-style', ABFP_PLUGIN_DIR_URL . 'assets/css/shortcode.css', array(), filemtime( $shortcode_css_path ) );
	}

	public function post_search_enqueue_scripts() {
		wp_register_script(
			'post-search',
			ABFP_PLUGIN_DIR_URL . 'assets/js/ajax-post-search.js',
			array( 'jquery' )
		);

		wp_localize_script( 'post-search', 'AjaxPostSearch', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'post_search' ),
		) );
	}
}

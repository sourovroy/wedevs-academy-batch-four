<?php

class ABFP_React_Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function admin_menu() {
		add_menu_page(
			'React Settings',
			'React Settings',
			'administrator',
			'abfp_react_settings',
			array( $this, 'react_settings_callback' )
		);
	}

	public function react_settings_callback() {
		echo '<div id="abfp-react-settings-app"></div>';
	}

	public function admin_enqueue_scripts( $screen ) {
		if ( 'toplevel_page_abfp_react_settings' == $screen ) {
			$settings_asset = require ABFP_PLUGIN_DIR_PATH . 'assets/build/main.asset.php';

			wp_enqueue_script( 'settings-main', ABFP_PLUGIN_DIR_URL . 'assets/build/main.js', $settings_asset['dependencies'], $settings_asset['version'], true );
			wp_enqueue_style( 'settings-main', ABFP_PLUGIN_DIR_URL . 'assets/build/main.css', array(), $settings_asset['version'] );
		}
	}

}

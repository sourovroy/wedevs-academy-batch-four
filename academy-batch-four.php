<?php
/*
Plugin Name: Academy Batch Four Plugin
Description: This is a Academy Batch Four Plugin description.
Version: 1.0.0
Author: weDevs Academy
Author URI: https://sourov.xyz
License: GPLv2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: academy-batch-four
 */

class ABFP_Academy_Batch_Four_Plugin {

	private static $instance = null;

	private function __construct() {
		// add_filter( 'the_content', array( $this, 'academy_the_content' ) );

		// add_action( 'wp_footer', 'ABFP_Academy_Batch_Four_Plugin::wp_footer' );

		$this->define_constants();

		$this->load_classes();
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function academy_the_content( $content ) {
		$show = apply_filters( 'show_page_content_qr_code', true );

		$new_content = '';

		if ( $show ) {
			$url = get_the_permalink();

			$new_content = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $url . '">';
		}

		return $content . $new_content;
	}

	public static function wp_footer() {
		do_action( 'before_footer_qr_code' );

		$url = home_url();

		$new_content = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $url . '">';

		echo $new_content;
	}

	private function load_classes() {
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/helpers.php';
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Admin_Menu.php';
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Custom_Columns.php';
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Post_type.php';
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Metabox.php';
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Book_Reader.php';
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Enqueue.php';
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Shortcode.php';
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Settings.php';
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Ajax.php';

		// new ABFP_Admin_Menu();
		// new Custom_Columns();
		// new ABFP_Post_type();
		// new ABFP_Metabox();
		// new ABFP_Book_Reader();

		new ABFP_Enqueue();
		new ABFP_Shortcode();
		// new ABFP_Settings();
		new ABFP_Ajax();
	}

	private function define_constants() {
		define( 'ABFP_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
		define( 'ABFP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
	}
}

ABFP_Academy_Batch_Four_Plugin::get_instance();

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
		require_once ABFP_PLUGIN_DIR_PATH . 'includes/Admin_Menu.php';

		new ABFP_Admin_Menu();
	}

	private function define_constants() {
		define( 'ABFP_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
	}
}

ABFP_Academy_Batch_Four_Plugin::get_instance();

add_filter( 'show_page_content_qr_code', function( $is_show ) {
	return true;
}, 11 );

// Another Plugin
add_filter( 'show_page_content_qr_code', function( $is_show ) {
	$is_show = false;

	return $is_show;
}, 10 );

function abfp_before_footer_qr_code() {
	?>
	<p>This is before QR Code.</p>
	<?php
}

add_action( 'before_footer_qr_code', 'abfp_before_footer_qr_code', 20 );

// remove_action( 'before_footer_qr_code', 'abfp_before_footer_qr_code', 20 );

function abfp2_before_footer_qr_code() {
	?>
	<p>This is before QR Code extra.</p>
	<?php
}
add_action( 'before_footer_qr_code', 'abfp2_before_footer_qr_code', 21 );

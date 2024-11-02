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
        add_filter( 'the_content', array( $this, 'academy_the_content' ) );

        add_action( 'wp_footer', 'ABFP_Academy_Batch_Four_Plugin::wp_footer' );
    }

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function academy_the_content( $content ) {
        $url = get_the_permalink();

        $new_content = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $url . '">';

        return  $content . $new_content;
    }

    public static function wp_footer() {
        $url = home_url();

        $new_content = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $url . '">';

        echo $new_content;
    }
}

ABFP_Academy_Batch_Four_Plugin::get_instance();

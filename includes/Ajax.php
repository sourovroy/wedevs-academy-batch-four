<?php

class ABFP_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_abfp_ajax_post_search', array( $this, 'abfp_ajax_post_search' ) );
		add_action( 'wp_ajax_nopriv_abfp_ajax_post_search', array( $this, 'abfp_ajax_post_search' ) );
	}

	public function abfp_ajax_post_search() {
		// Verify nonce.
		check_ajax_referer( 'post_search', 'nonce' );

		$post_args = array(
			'post_type' => 'post',
			'posts_per_page' => 10,
		);

		if ( ! empty( $_POST['cat_id'] ) ) {
			$post_args['cat'] = $_POST['cat_id'];
		}

		$posts = get_posts( $post_args );

		wp_send_json( $posts );
	}
}

<?php

class ABFP_Admin_Menu {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		add_menu_page(
			'Query Posts',
			'Query Posts',
			'administrator',
			'abfp_query_posts',
			array( $this, 'query_posts_callback' )
		);
	}

	public function query_posts_callback() {
		$abfp_category_id = 0;

		if ( isset( $_GET['abfp_category_id'] ) ) {
			$abfp_category_id = $_GET['abfp_category_id'];
		}

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => '-1',
		);

		if ( ! empty( $abfp_category_id ) ) {
			$args['cat'] = $abfp_category_id;
		}

		$posts = get_posts( $args );

		$terms = get_terms( array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
		) );

		include ABFP_PLUGIN_DIR_PATH . 'includes/templates/query-posts.php';
	}
}

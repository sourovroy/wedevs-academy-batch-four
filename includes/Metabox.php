<?php

class ABFP_Metabox{
	public function __construct() {

		if ( file_exists( ABFP_PLUGIN_DIR_PATH . 'lib/CMB2/init.php' ) ) {
			require_once ABFP_PLUGIN_DIR_PATH . 'lib/CMB2/init.php';
		}

		add_action( 'cmb2_admin_init', array( $this, 'page_meta_boxes' ) );

		add_filter( 'the_content', array( $this, 'the_content' ) );
	}

	public function page_meta_boxes() {
		$box1 = new_cmb2_box( array(
			'id' => 'cmb2-metabox-area',
			'title' => 'Metabox Area',
			'object_types' => array( 'page' ),
		) );

		$box1->add_field( array(
			'id' => 'cmb2_text_field',
			'type' => 'text',
			'name' => 'Enter Name',
			'desc' => 'Learn from here',
		) );

		$box1->add_field( array(
			'id' => 'cmb2_file_list_field',
			'type' => 'file_list',
			'name' => 'Enter Images',
		) );
	}

	public function the_content( $content ) {
		global $post;

		if ( get_post_type( $post ) != 'page' ) {
			return $content;
		}

		$images = get_post_meta( $post->ID, 'cmb2_file_list_field', true );
		ob_start();

		foreach ( $images as $key => $image ) :
		?>
		<img src="<?php echo $image; ?>" alt="">
		<?php
		do_action( 'after_gallery_image' );
		endforeach;

		return $content . ob_get_clean();
	}
}

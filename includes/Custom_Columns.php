<?php

class Custom_Columns {

	public function __construct() {
		add_filter( 'manage_page_posts_columns', array( $this, 'manage_posts_columns' ) );
		add_action( 'manage_page_posts_custom_column' , array( $this, 'custom_post_column' ), 10, 2 );

		add_filter( 'manage_edit-page_sortable_columns', array( $this, 'page_sortable_columns' ) );
	}

	public function manage_posts_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $column ) {
			$new_columns[ $key ] = $column;

			if ( 'cb' == $key ) {
				$new_columns['id'] = 'ID';
				$new_columns['thumbnail'] = 'Thumbnail';
			}
		}

		return $new_columns;
	}

	public function custom_post_column( $column_name, $post_id ) {
		if ( 'id' == $column_name ) {
			echo $post_id;
		}

		if ( 'thumbnail' == $column_name ) {
			$thumbnail_id = get_post_thumbnail_id( $post_id );

			if ( ! $thumbnail_id ) {
				return;
			}

			$image_src = wp_get_attachment_image_src( $thumbnail_id );
			?>
				<img src="<?php echo $image_src[0]; ?>" alt="" style="width: 50px; height: 50px; border-radius: 50%;">
			<?php
		}
	}

	public function page_sortable_columns( $columns ) {
		$columns['id'] = 'id';

		return $columns;
	}
}

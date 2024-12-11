<?php

class ABFP_Book_Reader {
	public function __construct() {
		if ( file_exists( ABFP_PLUGIN_DIR_PATH . 'lib/CMB2/init.php' ) ) {
			require_once ABFP_PLUGIN_DIR_PATH . 'lib/CMB2/init.php';
		}

		add_action( 'init', array( $this, 'init' ) );

		add_action( 'cmb2_admin_init', array( $this, 'book_meta_boxes' ) );
		add_filter( 'the_content', array( $this, 'book_the_content' ) );
		add_filter( 'the_content', array( $this, 'chapter_the_content' ) );
	}

	public function init() {
		register_post_type( 'book', array(
			'labels'             => array(
				'name'                  => 'Books',
				'singular_name'         => 'Book',
				'menu_name'             => 'Books',
				'add_new'               => 'Add New',
				'add_new_item'          => 'Add New Book',
				'new_item'              => 'New Book',
				'edit_item'             => 'Edit Book',
				'view_item'             => 'View Book',
				'all_items'             => 'All Books',
				'search_items'          => 'Search Books',
				'not_found'             => 'No book found.',
				'not_found_in_trash'    => 'No book found in Trash.',
				'items_list'            => 'Books list',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'rewrite'            => array( 'slug' => 'books' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
			'show_in_rest'       => true,
		) );

		register_post_type( 'chapter', array(
			'labels'             => array(
				'name'                  => 'Chapters',
				'singular_name'         => 'Chapter',
				'menu_name'             => 'Chapters',
				'add_new'               => 'Add New',
				'add_new_item'          => 'Add New Chapter',
				'new_item'              => 'New Chapter',
				'edit_item'             => 'Edit Chapter',
				'view_item'             => 'View Chapter',
				'all_items'             => 'All Chapters',
				'search_items'          => 'Search Chapters',
				'not_found'             => 'No chapter found.',
				'not_found_in_trash'    => 'No chapter found in Trash.',
				'items_list'            => 'Chapters list',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'rewrite'            => array( 'slug' => 'chapters' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
			'show_in_rest'       => true,
		) );
	}

	public function book_meta_boxes() {
		$book = new_cmb2_box( array(
			'id' => 'book-settings-area',
			'title' => 'Book Settings',
			'object_types' => array( 'chapter' ),
		) );

		$book->add_field( array(
			'id' => 'choose_book',
			'name' => 'Select Book Name',
			'desc' => 'Select book name which belong to the chapter',
			'type' => 'select',
			'show_option_none' => true,
			'options_cb' => array( $this, 'book_list_callback' ),
		) );
	}

	public function book_list_callback() {
		$query = get_posts( array(
			'post_type' => 'book',
			'posts_per_page' => -1,
		) );

		$books = array();

		foreach ( $query as $book ) {
			$books[ $book->ID ] = $book->post_title;
		}

		return $books;
	}

	public function book_the_content( $contents ) {
		global $post;

		if ( $post->post_type !== 'book' ) {
			return $contents;
		}

		// Book.
		$chapters = get_posts( array(
			'post_type' => 'chapter',
			'posts_per_page' => -1,
			'meta_key' => 'choose_book',
			'meta_value' => $post->ID,
		) );

		ob_start();
		?>
		<ul>
			<?php foreach ( $chapters as $chapter ) : ?>
				<li>
					<a href="<?php echo get_the_permalink( $chapter ); ?>">
						<?php echo $chapter->post_title; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		$html = ob_get_clean();

		return $contents . $html;
	}

	public function chapter_the_content( $contents ) {
		global $post;

		if ( $post->post_type !== 'chapter' ) {
			return $contents;
		}

		$book_id = get_post_meta( $post->ID, 'choose_book', true );

		$book = get_post( $book_id );


		$html = '<p>Go to <a href="' . get_the_permalink( $book ) . '">' . $book->post_title . '</a></p>';

		return $contents . $html;
	}
}

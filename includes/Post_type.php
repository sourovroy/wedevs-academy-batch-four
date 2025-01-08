<?php

class ABFP_Post_type {

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );

		add_filter( 'manage_book_posts_columns', array( $this, 'manage_posts_columns' ) );
		add_action( 'manage_book_posts_custom_column' , array( $this, 'custom_post_column' ), 10, 2 );
		add_action( 'quick_edit_custom_box' , array( $this, 'quick_edit_custom_box' ), 10, 2 );

		add_action( 'save_post_book' , array( $this, 'save_post_book' ), 10, 2 );

		add_filter( 'the_content', array( $this, 'category_contents' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_book' , array( $this, 'save_post_book_metabox' ), 10, 2 );
	}

	public function init() {
		register_post_type( 'book', array(
			'public' => true,
			'label' => 'Books',
			'hierarchical' => true,
			// 'publicly_queryable' => false,
			'show_in_admin_bar' => false,
			'show_in_nav_menus' => false,
			'show_in_rest' => true,
			'rest_base' => 'books',
			'menu_position' => 79,
			'menu_icon' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1NzYgNTEyIiBmaWxsPSIjZmZmIj48IS0tIUZvbnQgQXdlc29tZSBGcmVlIDYuNy4xIGJ5IEBmb250YXdlc29tZSAtIGh0dHBzOi8vZm9udGF3ZXNvbWUuY29tIExpY2Vuc2UgLSBodHRwczovL2ZvbnRhd2Vzb21lLmNvbS9saWNlbnNlL2ZyZWUgQ29weXJpZ2h0IDIwMjQgRm9udGljb25zLCBJbmMuLS0+PHBhdGggZD0iTTU0OS43IDEyNC4xYy02LjMtMjMuNy0yNC44LTQyLjMtNDguMy00OC42QzQ1OC44IDY0IDI4OCA2NCAyODggNjRTMTE3LjIgNjQgNzQuNiA3NS41Yy0yMy41IDYuMy00MiAyNC45LTQ4LjMgNDguNi0xMS40IDQyLjktMTEuNCAxMzIuMy0xMS40IDEzMi4zczAgODkuNCAxMS40IDEzMi4zYzYuMyAyMy43IDI0LjggNDEuNSA0OC4zIDQ3LjhDMTE3LjIgNDQ4IDI4OCA0NDggMjg4IDQ0OHMxNzAuOCAwIDIxMy40LTExLjVjMjMuNS02LjMgNDItMjQuMiA0OC4zLTQ3LjggMTEuNC00Mi45IDExLjQtMTMyLjMgMTEuNC0xMzIuM3MwLTg5LjQtMTEuNC0xMzIuM3ptLTMxNy41IDIxMy41VjE3NS4ybDE0Mi43IDgxLjItMTQyLjcgODEuMnoiLz48L3N2Zz4=',
			'supports' => array( 'title', 'editor', 'page-attributes', 'thumbnail' ),
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'my-books',
			),
			'labels' => array(
				'name' => 'Books',
				'singular_name' => 'Book',
				'add_new_item' => 'Create new book',
				'edit_item' => 'Edit Book',
				'view_item' => 'View Book',
				'item_published' => 'Book Published',
				'item_updated' => 'Book Updated',
			),
		) );

		register_taxonomy( 'book_category', 'book', array(
			'labels' => array(
				'name' => 'Categories',
				'singular_name' => 'Category',
			),
			'show_in_rest' => true,
			'hierarchical' => true,
		) );

		register_taxonomy( 'book_tag', 'book', array(
			'labels' => array(
				'name' => 'Tags',
				'singular_name' => 'Tag',
			),
			'show_in_rest' => true,
			'hierarchical' => false,
		) );
	}

	public function manage_posts_columns( $columns ) {
		$columns['something'] = 'Something';
		$columns['something2'] = 'Something2';

		return $columns;
	}

	public function custom_post_column( $column_name, $post_id ) {
		echo get_post_meta( $post_id, '_post_something', true );
	}

	public function quick_edit_custom_box( $column_name, $post_type ) {
		if ( $post_type != 'book' ) {
			return;
		}

		if ( $column_name != 'something' ) {
			return;
		}

		global $post;

		$post_something = get_post_meta( $post->ID, '_post_something', true );
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<label>
					<span class="title">Something</span>
					<span class="input-text-wrap">
						<input type="text" name="post_something" class="ptitle" value="<?php echo $post_something; ?>">
					</span>
				</label>
			</div>
		</fieldset>
		<?php
	}

	public function save_post_book( $post_id ) {

		if ( ! empty( $_POST['post_something'] ) ) {
			update_post_meta( $post_id, '_post_something', $_POST['post_something'] );
		}

	}

	public function category_contents( $content ) {
		// If is the not single post page.
		if ( ! is_book() ) {
			return $content;
		}

		global $post;

		$book_categories = wp_get_post_terms( $post->ID, 'book_category' );
		$cat_count = count( $book_categories );
		$book_tags = wp_get_post_terms( $post->ID, 'book_tag' );
		$tags_count = count( $book_tags );
		ob_start();
		?>
		<p>
			<strong>Categories:</strong>
			<?php foreach ( $book_categories as $index => $book_category ) : ?>
				<?php
					echo $book_category->name;

					if ( ($index + 1) != $cat_count ) {
						echo ',';
					}
				?>
			<?php endforeach; ?>
		</p>
		<p>
			<strong>Tags:</strong>
			<?php foreach ( $book_tags as $index => $book_tag ) : ?>
				<a href="<?php echo get_term_link( $book_tag ); ?>">
					<?php echo $book_tag->name; ?>
				</a>
				<?php
					if ( ($index + 1) != $tags_count ) {
						echo ',';
					}
				?>
			<?php endforeach; ?>
		</p>
		<?php
		$content .= ob_get_clean();

		return $content;
	}

	public function add_meta_boxes() {
		add_meta_box(
			'abfp-custom-meta-box',
			'Our Custom Metabox',
			array( $this, 'custom_meta_box_callback' ),
			array('post', 'book' )
		);
	}

	public function custom_meta_box_callback( $post ) {
		$abfp_descrioption = get_post_meta( $post->ID, 'abfp_descrioption', true );
		$abfp_choose_one = get_post_meta( $post->ID, 'abfp_choose_one', true );
		?>
		<p>
			<label for="">Description:</label>
			<input type="text" name="abfp_descrioption" value="<?php echo $abfp_descrioption ? $abfp_descrioption : '' ?>">
		</p>
		<p>
			<label for="">Choose any one</label>
			<select name="abfp_choose_one" id="">
				<option value="1" <?php selected( $abfp_choose_one, '1' ); ?>>1</option>
				<option value="2" <?php selected( '2', $abfp_choose_one ); ?>>2</option>
				<option value="3" <?php selected( '3', $abfp_choose_one ); ?>>3</option>
			</select>
		</p>
		<?php
	}

	public function save_post_book_metabox( $post_id ) {
		if ( isset( $_POST['abfp_descrioption'] ) ) {
			update_post_meta( $post_id, 'abfp_descrioption', $_POST['abfp_descrioption'] );
		}

		if ( isset( $_POST['abfp_choose_one'] ) ) {
			update_post_meta( $post_id, 'abfp_choose_one', $_POST['abfp_choose_one'] );
		}
	}
}

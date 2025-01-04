<?php

class ABFP_Shortcode {

	public function __construct() {
		// add_shortcode( 'show_page_qr', array( $this, 'show_page_qr' ) );
		// add_shortcode( 'query_posts', array( $this, 'query_posts' ) );
		// add_shortcode( 'search_form', array( $this, 'search_form' ) );
		add_shortcode( 'ajax_post_search', array( $this, 'ajax_post_search' ) );
	}

	public function show_page_qr() {
		wp_enqueue_style( 'shortcode-style' );
		$url = get_the_permalink();

		ob_start();
		?>
		<p class="qr-code-image">
			<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo $url; ?>">
		</p>
		<?php
		return ob_get_clean();
	}

	public function query_posts( $atts, $content = null  ) {
		$attr = shortcode_atts( array(
			'post_type' => 'post',
			'posts_per_page' => 10,
			'html_element' => 'ul',
		), $atts );

		$posts = get_posts( $attr );

		ob_start();

		if ( $content ) {
			echo '<h3>' . $content . '</h3>';
		}

		$html_element = preg_replace( '/[^a-z]/i', '', $attr['html_element'] );

		echo '<' . $html_element . '>';

		foreach ( $posts as $post ) {
			if ( $html_element == 'ul' ) {
				echo '<li>';
			} else {
				echo '<p>';
			}

			echo $post->post_title;

			if ( $html_element == 'ul' ) {
				echo '</li>';
			} else {
				echo '</p>';
			}
		}

		echo '</' . $html_element . '>';

		return ob_get_clean();
	}

	public function search_form() {
		$search = isset( $_POST['custom_search'] ) ? sanitize_text_field($_POST['custom_search']) : '';

		$posts = array();
		$has_query = false;

		if ( strlen( $search ) > 2 ) {
			$has_query = true;
			$posts = get_posts( array(
				'post_type' => 'post',
				's' => $search,
			) );
		}

		ob_start();

		?>

		<form action="<?php the_permalink(); ?>" method="POST">
			<p>
				<input type="text" value="<?php echo isset( $_POST['custom_search'] ) ? esc_attr($_POST['custom_search']) : ''; ?>" name="custom_search">
				<button type="submit">Search</button>
			</p>

			<?php if ( isset( $_POST['custom_search'] ) && strlen( $search ) < 3 ) : ?>
				<h4>Please enter search text.</h4>
			<?php endif; ?>

			<?php if ( isset( $_POST['custom_search'] ) && count( $posts ) == 0 && $has_query ) : ?>
				<h4>No items found.</h4>
			<?php endif; ?>

			<?php foreach ( $posts as $post ): ?>
				<p><?php echo $post->post_title; ?></p>
			<?php endforeach; ?>
		</form>
		<?php

		return ob_get_clean();
	}

	public function ajax_post_search() {
		wp_enqueue_script( 'post-search' );

		$terms = get_terms( array(
			'taxonomy'   => 'category',
		) );

		ob_start();
		?>
			<div>
				<select id="post-search-category-select">
					<option value="0">Choose Category</option>
					<?php foreach ( $terms as $term ) : ?>
					<option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div id="ajax-post-search-items">Searching...</div>
		<?php
		return ob_get_clean();
	}
}

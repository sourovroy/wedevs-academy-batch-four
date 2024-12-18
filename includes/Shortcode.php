<?php

class ABFP_Shortcode {

	public function __construct() {
		add_shortcode( 'show_page_qr', array( $this, 'show_page_qr' ) );
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
}

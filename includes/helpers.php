<?php

/**
 * Check the details page is under book post type.
 */
function is_book() {
	return is_singular( 'book' );
}

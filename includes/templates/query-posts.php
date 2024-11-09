<div class="wrap">
	<h1>Query Posts</h1>

	<div class="tablenav top">
		<form action="<?php echo admin_url( '/admin.php' ); ?>" method="get">
			<input type="hidden" name="page" value="abfp_query_posts">
			<div class="alignleft actions bulkactions">
				<select name="abfp_category_id" id="bulk-action-selector-top">
					<option value="0">Select Category</option>
					<?php foreach ( $terms as $term ) : ?>
					<option value="<?php echo $term->term_id; ?>" <?php echo $abfp_category_id == $term->term_id ? 'selected' : ''; ?> ><?php echo $term->name; ?></option>
					<?php endforeach; ?>
				</select>
				<input type="submit" id="doaction" class="button action" value="Apply">
			</div>
		</form>
	</div>

	<table class="wp-list-table widefat fixed striped table-view-list posts">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Author</th>
				<th>Category</th>
				<th>Date Diff</th>
				<th>Date Format</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $posts as $post ) : ?>
			<tr>
				<td><?php echo $post->ID; ?></td>
				<td><?php echo $post->post_title; ?></td>
				<td>
					<?php
						$author_object = get_user_by('id', $post->post_author);
						echo $author_object->display_name;
					?>
				</td>
				<td>
					<?php
						$categories = wp_get_post_terms( $post->ID, 'category' );
						$category_names = array();
						foreach( $categories as $category ) {
							$category_names[] = $category->name;
						}
						echo implode( ", ", $category_names );
					?>
				</td>
				<td>Posted <?php echo human_time_diff( strtotime( $post->post_date ) ); ?> ago</td>
				<td><?php echo wp_date( 'F j, Y g:i a', strtotime( $post->post_date ) ); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

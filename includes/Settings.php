<?php

class ABFP_Settings {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		add_menu_page(
			'Academy Settings',
			'Academy Settings',
			'manage_options',
			'academy_settings',
			array( $this, 'academy_settings' ),
			'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="#9DA1A7" width="18" height="18"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M192 512C86 512 0 426 0 320C0 228.8 130.2 57.7 166.6 11.7C172.6 4.2 181.5 0 191.1 0l1.8 0c9.6 0 18.5 4.2 24.5 11.7C253.8 57.7 384 228.8 384 320c0 106-86 192-192 192zM96 336c0-8.8-7.2-16-16-16s-16 7.2-16 16c0 61.9 50.1 112 112 112c8.8 0 16-7.2 16-16s-7.2-16-16-16c-44.2 0-80-35.8-80-80z"/></svg>' ),
			3
		);

		add_submenu_page(
			'academy_settings',
			'Sub Settings',
			'Sub Settings',
			'manage_options',
			'sub_settings',
			array( $this, 'sub_settings' )
		);

		add_options_page(
			'Sub Option',
			'Sub Option',
			'manage_options',
			'sub_option',
			array( $this, 'sub_settings' )
		);

		add_submenu_page(
			'edit.php?post_type=page',
			'Sub Tools',
			'Sub Tools',
			'manage_options',
			'sub_tools',
			array( $this, 'sub_settings' )
		);
	}

	public function academy_settings() {
		$errros = array();

		if ( isset( $_POST['submit'] ) ) {
			// Validation.
			if ( empty( $_POST['email_address'] ) ) {
				$errros['email_address'] = 'Please enter email address';
			} elseif( ! is_email( $_POST['email_address'] ) ) {
				$errros['email_address'] = 'Email address is not valid.';
			}

			if ( empty( $_POST['your_choice'] ) ) {
				$errros['your_choice'] = 'Please choose your option.';
			}

			// Save data.
			if ( count( $errros ) == 0 ) {
				$data = array(
					'email_address' => sanitize_text_field( $_POST['email_address'] ),
					'your_choice' => intval( $_POST['your_choice'] ),
				);

				update_option( 'abfp_settings', $data );
			}
		}

		$saved_data = get_option( 'abfp_settings', array() );

		if( ! isset( $saved_data['your_choice'] ) ) {
			$saved_data['your_choice'] = 1;
		}
		?>
		<div class="wrap">
			<h1>Academy Settings</h1>

			<form action="<?php echo admin_url(); ?>admin.php?page=academy_settings" method="post">
				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<label>Enter Email Address</label>
							</th>
							<td>
								<input type="text" class="regular-text" name="email_address" value="<?php echo esc_attr( isset( $saved_data['email_address'] ) ? $saved_data['email_address'] : '' ); ?>">
								<p>
									<?php
										if ( isset( $errros['email_address'] ) ) {
											echo $errros['email_address'];
										}
									?>
								</p>
							</td>
						</tr>
						<tr>
							<th>
								<label>You choice</label>
							</th>
							<td>
								<select name="your_choice">
									<option value="1" <?php echo $saved_data['your_choice'] == 1 ? 'selected' : ''; ?>>1</option>
									<option value="2" <?php echo $saved_data['your_choice'] == 2 ? 'selected' : ''; ?>>2</option>
									<option value="3" <?php echo $saved_data['your_choice'] == 3 ? 'selected' : ''; ?>>3</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
				</p>
			</form>
		</div>
		<?php
	}

	public function sub_settings() {

	}
}

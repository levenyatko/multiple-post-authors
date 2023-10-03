<table class="form-table guest-author-meta-table">
	<tbody>
		<tr>
			<th>
				<label class="post-attributes-label" for="parent_id">
					<?php esc_html_e( 'First Name', 'multi-post-authors' ); ?>
				</label>
			</th>
			<td>
				<input type="text" name="first-name" value="<?php echo esc_attr( $args['first-name'] ?? '' ); ?>">
			</td>
		</tr>
		<tr>
			<th>
				<label class="post-attributes-label" for="parent_id">
					<?php esc_html_e( 'Last Name', 'multi-post-authors' ); ?>
				</label>
			</th>
			<td>
				<input type="text" name="last-name" value="<?php echo esc_attr( $args['last-name'] ?? '' ); ?>">
			</td>
		</tr>
		<tr>
			<th>
				<label class="post-attributes-label" for="parent_id">
					<?php esc_html_e( 'Display Name', 'multi-post-authors' ); ?>
				</label>
			</th>
			<td>
				<input type="text" name="display-name" value="<?php echo esc_attr( $args['display-name'] ?? '' ); ?>">
			</td>
		</tr>
		<tr>
			<th>
				<label class="post-attributes-label" for="parent_id">
					<?php esc_html_e( 'Email', 'multi-post-authors' ); ?>
				</label>
			</th>
			<td>
				<input type="email" name="email" value="<?php echo esc_attr( $args['email'] ?? '' ); ?>">
			</td>
		</tr>
		<tr>
			<th>
				<label class="post-attributes-label" for="parent_id">
					<?php esc_html_e( 'Biographical Info', 'multi-post-authors' ); ?>
				</label>
			</th>
			<td>
				<textarea name="biography"><?php echo esc_textarea( $args['biography'] ?? '' ); ?></textarea>
			</td>
		</tr>
	</tbody>
</table>
<?php wp_nonce_field( 'mpa-guestauthor-save', 'mpa-guestauthor-nonce' ); ?>
<style>
	.guest-author-meta-table td>input,
	.guest-author-meta-table td>textarea {
		width: 50%;
	}
</style>

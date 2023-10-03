<ul class="mpa-post-authors-list authors-current-user-can-assign">
	<?php
	if ( ! empty( $args['authors'] ) ) {
		foreach ( $args['authors'] as $author ) {
			?>
				<li class="mpa-post-authors-list__item">
					<div class="display-name">
						<span class="dashicons dashicons-menu"></span>
					<?php echo wp_kses_post( $author->display_name ); ?>
					</div>
					<divs class="author-item-remove">
						<span class="dashicons dashicons-no-alt"></span>
					</divs>
					<input type="hidden" name="mpa_authors[]" value="<?php echo esc_attr( $author->ID ); ?>">
				</li>
				<?php
		}
	}
	?>
</ul>
<?php wp_nonce_field( 'mpa-authors-save', 'mpa-authors-nonce' ); ?>
<div class="post-authors-search" >
	<input type="search"
			autocomplete="off"
			autocorrect="off"
			autocapitalize="none"
			spellcheck="false"
			class="post-authors-search-field"
			placeholder="<?php esc_attr_e( 'Search for an author', 'multi-post-authors' ); ?>"
	>
	<div class="mpa-search-results"></div>
</div>
<script type="text/html" id="tmpl-post-authors-partial">
	<li class="mpa-post-authors-list__item">
		<div class="display-name">
			<span class="dashicons dashicons-menu"></span>
			{{{ data.title }}}
		</div>
		<divs class="author-item-remove">
			<span class="dashicons dashicons-no-alt"></span>
		</divs>
		<input type="hidden" name="mpa_authors[]" value="{{{ data.value }}}">
	</li>
</script>


<h2><?php _e( 'Personal Options' ); ?></h2>

<table class="form-table">
<?php if ( ! ( IS_PROFILE_PAGE && ! $user_can_edit ) ) : ?>
	<tr class="user-rich-editing-wrap">
		<th scope="row"><?php _e( 'Visual Editor' ); ?></th>
		<td><label for="rich_editing"><input name="rich_editing" type="checkbox" id="rich_editing" value="false" <?php if ( ! empty( $profileuser->rich_editing ) ) checked( 'false', $profileuser->rich_editing ); ?> /> <?php _e( 'Disable the visual editor when writing' ); ?></label></td>
	</tr>
<?php endif; ?>
<?php if ( count($_wp_admin_css_colors) > 1 && has_action('admin_color_scheme_picker') ) : ?>
<tr class="user-admin-color-wrap">
<th scope="row"><?php _e('Admin Color Scheme')?></th>
<td><?php
	/**
	 * Fires in the 'Admin Color Scheme' section of the user editing screen.
	 *
	 * The section is only enabled if a callback is hooked to the action,
	 * and if there is more than one defined color scheme for the admin.
	 *
	 * @since 3.0.0
	 * @since 3.8.1 Added `$user_id` parameter.
	 *
	 * @param int $user_id The user ID.
	 */
	do_action( 'admin_color_scheme_picker', $user_id );
?></td>
</tr>
<?php
endif; // $_wp_admin_css_colors
if ( !( IS_PROFILE_PAGE && !$user_can_edit ) ) : ?>
<tr class="user-comment-shortcuts-wrap">
<th scope="row"><?php _e( 'Keyboard Shortcuts' ); ?></th>
<td><label for="comment_shortcuts"><input type="checkbox" name="comment_shortcuts" id="comment_shortcuts" value="true" <?php if ( ! empty( $profileuser->comment_shortcuts ) ) checked( 'true', $profileuser->comment_shortcuts ); ?> /> <?php _e('Enable keyboard shortcuts for comment moderation.'); ?></label> <?php _e('<a href="https://codex.wordpress.org/Keyboard_Shortcuts" target="_blank">More information</a>'); ?></td>
</tr>
<?php endif; ?>
<tr class="show-admin-bar user-admin-bar-front-wrap">
<th scope="row"><?php _e( 'Toolbar' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Toolbar') ?></span></legend>
<label for="admin_bar_front">
<input name="admin_bar_front" type="checkbox" id="admin_bar_front" value="1"<?php checked( _get_admin_bar_pref( 'front', $profileuser->ID ) ); ?> />
<?php _e( 'Show Toolbar when viewing site' ); ?></label><br />
</fieldset>
</td>
</tr>

<?php
$languages = get_available_languages();
if ( $languages ) : ?>
<tr class="user-language-wrap">
	<th scope="row">
		<?php /* translators: The user language selection field label */ ?>
		<label for="locale"><?php _e( 'Language' ); ?></label>
	</th>
	<td>
		<?php
		$user_locale = $profileuser->locale;

		if ( 'en_US' === $user_locale ) {
			$user_locale = '';
		} elseif ( '' === $user_locale || ! in_array( $user_locale, $languages, true ) ) {
			$user_locale = 'site-default';
		}

		wp_dropdown_languages( array(
			'name'                        => 'locale',
			'id'                          => 'locale',
			'selected'                    => $user_locale,
			'languages'                   => $languages,
			'show_available_translations' => false,
			'show_option_site_default'    => true
		) );
		?>
	</td>
</tr>
<?php
endif;
?>

<?php
/**
 * Fires at the end of the 'Personal Options' settings table on the user editing screen.
 *
 * @since 2.7.0
 *
 * @param WP_User $profileuser The current WP_User object.
 */
do_action( 'personal_options', $profileuser );
?>

</table>


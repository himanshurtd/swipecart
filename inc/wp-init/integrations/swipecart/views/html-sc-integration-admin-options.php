<?php
/**
 * Admin View: Page - Admin options.
 *
 * @package WooCommerce\Integrations
 */

defined('ABSPATH') or die('No script kiddies please!');

?>

<table class="form-table">
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label>
				<?php esc_html_e('Authentication Token', 'swipecart'); ?>
			</label>
		</th>
		<td class="forminp">
			<fieldset>
				<legend class="screen-reader-text"><span><?php esc_html_e('Authentication Token', 'swipecart'); ?></span></legend>
				<input class="input-text regular-input" type="text" value="<?php echo $this->authCombo['auth_token']; ?>" readonly>
				<p class="description"><?php esc_html_e('This Authentication Token required for Mobile API.', 'swipecart'); ?></p>
			</fieldset>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" class="titledesc">
			<label>
				<?php esc_html_e('Authentication Secret', 'swipecart'); ?>
			</label>
		</th>
		<td class="forminp">
			<fieldset>
				<legend class="screen-reader-text"><span><?php esc_html_e('Authentication Secret', 'swipecart'); ?></span></legend>
				<input class="input-text regular-input" type="text" value="<?php echo $this->authCombo['auth_secret']; ?>" readonly>
				<p class="description"><?php esc_html_e('Authentication Secret is hidden key used once for the app.', 'swipecart'); ?></p>
			</fieldset>
		</td>
	</tr>
</table>

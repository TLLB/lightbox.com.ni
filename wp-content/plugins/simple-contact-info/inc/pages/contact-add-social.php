<?php
// Block direct requests
if ( !defined('ABSPATH') ) {
	exit();
}

// ------------- Functions -------------

function sciGetOptions() {
	global $wpdb;

	return $wpdb->get_results("SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE 'qs_contact_custom_%'");
}

function sciShowCustomOptions() {
	global $wpdb;

	$results = $wpdb->get_results("SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE 'qs_contact_custom_%'");

	$out = array();
	if (!empty($results)) {
		foreach ($results as $result) {
			$out[] = str_replace('qs_contact_custom_', 'show_', $result->option_name);
		}
	}

	return $out;
}

function sciContactInfoAddSocial() {
	$hidden_field_name = 'add_social';

	if (isset($_POST['name']) && $_POST[$hidden_field_name] == 'Y') {
		add_action('admin_notices', 'sciUpdatedNotice');
		if (!empty($_POST['name'])) {
			$name = str_replace(' ', '_', trim(strip_tags(strtolower($_POST['name'])))); 

			add_option('qs_contact_custom_'.$name);
			add_option('qs_contact_'.$name.'_icon');
			$msg = __(' was successfully added.', 'simple-contact-info');
			sciUpdatedNotice(ucfirst($name).$msg);
		} else {
			$msg = __('You forgot write name.', 'simple-contact-info');
			sciUpdatedNotice($msg);	
		}
	}
	$options = sciGetOptions();
	?>
	<div class="wrap">
		<div class="icon-sci-contact">
			<img src="<?php echo SCI_URL.'css/contact-info-icon.png'; ?>" alt="">
		</div>
		<h2><?php _e('Simple Add New Social Network', "simple-contact-info"); ?></h2>
		<form name="contactAddSocial" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="name"><?php _e("Name", "simple-contact-info"); ?></label></th>
						<td>
							<input id="name" name="name" class="regular-text" type="text" value="" />
							<br />
							<span class="description"><?php _e("Just add new Social Network.", "simple-contact-info"); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
				<input id="submit" class="button button-primary" type="submit" value="<?php _e('Add New' , 'simple-contact-info'); ?>" name="submit" />
			</p>
		</form>
		<?php if (!empty($options)) : ?>
			<h3><?php _e('Remove Custom Social Network', "simple-contact-info"); ?></h3>
				<table class="form-table">
					<tbody>
						<?php foreach ($options as $option) : ?>
							<tr>
								<th><label for="delete"><?php echo ucfirst(str_replace('qs_contact_custom_', '', $option->option_name)); ?></label></th>
								<td>
									<a id="delete" class="sci-delete-option remove" href="javascript:void(0);"><?php _e("Delete", "simple-contact-info"); ?></a>
									<input class="sci-option" type="hidden" name="name" value="<?php echo $option->option_name; ?>">
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
		<?php endif; ?>
	</div>
	<div id="dialog-confirm" title="<?php _e('Delete social network?', "simple-contact-info"); ?>" style="display: none;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php _e('These social network will be permanently deleted and cannot be recovered. Are you sure?', "simple-contact-info"); ?></p>
	</div>
<?php }
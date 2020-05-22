<?php 
	$existing_intent = null;

	$options = get_option('forms');
	$query_params = $this->ms4wp_get_query_params();
	$popup_settings = isset($query_params['form_id']) ? $options[$query_params['form_id']]->popup_settings : null;

	if($popup_settings !== null){
        $existing_intent = filter_var($popup_settings['exitIntent'], FILTER_VALIDATE_BOOLEAN);
	}
	
?>

<input type="checkbox" name="exit-intent" id="exit-intent" <?php echo ($existing_intent == false) ?: 'checked'; ?>/>
<p class="help">
	<?php _e( 'Show the optin when a user is about to leave your website by navigating their mouse outside the window.', $this->plugin_name ); ?>
</p>
<?php 
	$options = get_option('moosend-for-wp-general-settings-section');
	$api_key = $options['api_key'];
?>

<input type="text" class="widefat" placeholder="<?php _e( 'Your moosend API key', $this->plugin_name ); ?>" id="<?php echo $this->plugin_name; ?>-api_key" name="moosend-for-wp-general-settings-section[api_key]" value="<?php if(!empty($api_key)) echo $api_key; ?>"/>
<p class="help">
	<?php _e( 'The API key for connecting with your moosend account.', $this->plugin_name ); ?>
	<a target="_blank" href="#" id="opens_window">
		<?php _e('Get your API key here.', $this->plugin_name ); ?>		
	</a>
</p>

<div id="modal" class="form-table">
	<input type="text" placeholder="Your Login Domain" name="user-subdomain" id="user-subdomain"/>
	<span>.moosend.com</span>
	<input type="submit" id="modal-button" value="Get Me There" class="button moosend-orange-button"/>
</div>

	
	





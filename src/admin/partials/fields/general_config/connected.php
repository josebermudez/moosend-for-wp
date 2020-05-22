<?php 
	$options = get_option('moosend-for-wp-general-settings-section');
	$api_key = $options['api_key'];
	$status = $this->ms4wp_get_user_status($api_key);

	echo '<div id="api-key-status">';
	if($api_key == null):
		echo '<span id="api-key-missing">Not Connected</span>';
	elseif(($status & 1) == 0):
		echo '<span id="activation-pending">Confirmation Pending</span>';
	elseif(($status & 1) == 1):
		echo '<span id="connected">Connected</span>';
	endif;
	echo '</div>';
?>
<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       moosend.com
 * @since      1.0.0
 *
 * @package    Moosend_For_Wp
 * @subpackage Moosend_For_Wp/admin/partials
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="wrap">
	<p class="breadcrumbs">
			<strong>Moosend For WordPress</strong>
		</span>
	</p>
	<hr>


	<div>
		<div class="col-8-12">
			<?php settings_errors(); ?>

			<div class="page-title">
				<h1 class="ms4wp-title" style="font-weight: normal"><?php _e("General Settings", $this->plugin_name) ?></h1>
			</div>
			<div class="content">
				<form method="post" id="api-key-form" action="options.php">
					<?php 
						$options = get_option('moosend-for-wp-general-settings-section');
						$api_key = $options['api_key'];
					?>

					<?php
						settings_fields( 'general-settings-group');
						do_settings_sections( 'moosend-for-wp-general-settings-section' );
					?>

					<?php submit_button('Connect & Save API Key', 'api-key-button', 'api-key-button'); ?>
				</form>
			</div>
		</div>
		<div class="col-4-12">
			<div class="content" id="ms-registration-form">
				<?php 
				if($this->api_key == null){
					include_once('fields/general_config/ms4wp-sing-up-form.php'); 
				}else{
					include_once('fields/general_config/ms4wp-add-form-redirect.php');
				}
				?>
			</div>
		</div>
	</div>
</div>
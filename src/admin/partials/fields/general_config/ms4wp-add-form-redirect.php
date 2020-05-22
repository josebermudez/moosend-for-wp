<div class="form-banner">
	<img alt="moosend" src="https://moosend.com/wp-content/themes/moosend_theme/images/moosend_logo_full.svg"/>
</div>

<h3><?php _e("Welcome To Moosend For Wordpress!!", $this->plugin_name) ?></h3>
<p style="font-weight: bold;color: #545C6E"><?php _e("You can now create your own moosend subscription forms.", $this->plugin_name) ?></p>
<div id="create-forms-wrapper">
<a id="create-your-forms-hidden" href="<?php echo admin_url( 'admin.php?page='.$this->plugin_name.'-add-form' ); ?>" hidden></a>
<input id="create-your-forms" type="submit" value="Create Form" class="button">
</div>

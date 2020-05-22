<?php 
	$user_data = get_userdata(get_current_user_id());
	$user_email = !empty($user_data) ? $user_data->data->user_email : null;
?>

<div class="form-banner">
	<img alt="moosend" src="https://moosend.com/wp-content/themes/moosend_theme/images/moosend_logo_full.svg"/>
</div>

<h3><?php _e("You Don't Have An Account?", $this->plugin_name) ?></h3>
<form id="sign-up-form" method="post" target="_blank">
	<ul id="form-style">
	<li><label for="Name">Full Name*: </label>
	<input type="text" name="Name"/></li>
	<li id="email-list-item"><label for="Email">Email*: </label>
	<input type="email" name="Email" id="sign-up-email" value="<?php echo $user_email ?>"/></li>
	<li><label for="ClearPassword">Password*: </label>
	<input type="password" name="ClearPassword" id="password"/></li>
	<li><label for="Company">Company*: </label>
	<input type="text" name="Company"/></li>
	<li><label for="SiteName">Site Name*: </label>
	<input type="text" name="SiteName" id="sign-up-sitename"/></li>
	
	<div id="sign-up-button-wrapper">
	<input type="submit" id="sign-up-button" value="Sign Up" class="button">
	</div>
	</ul>
</form>

<?php
$options = get_option('forms');
$query_params = $this->ms4wp_get_query_params();
$form = isset($query_params['form_id']) ? $options[$query_params['form_id']] : null;

$val_exists = false;

if(isset($form)){
	$val_exists = ($form->member_name == "true") ? true : false;
}

?>

<input type="checkbox" id="member-name" name="member-name" <?php echo($val_exists ? 'checked' : '') ?>/>

<?php
$existing_subscription_url = null;
$options = get_option('forms');
$query_params = $this->ms4wp_get_query_params();
$form = isset($query_params['form_id']) ? $options[$query_params['form_id']] : null;

if (isset($query_params['form_id'])) {
    $existing_subscription_url = $form->after_subscription ?: null;
    $selected_new_tab = $form->new_tab == "true" ? 'checked' : '';
}
?>

<input type="url" pattern="https?://.+" name="after-subscription" id="after-subscription" class="regular-text"
       placeholder="<?php echo 'http://yourdomain.com/thanks' ?>" onfocus="this.placeholder = ''"
       onblur="this.placeholder = 'http://yourdomain.com/thanks'" value="<?php _e($existing_subscription_url); ?>"/>
<fieldset>
    <label title='New Tab'>
        <input type="checkbox" id="new-tab" name="new-tab" <?php echo $selected_new_tab; ?> />
        <span><?php esc_attr_e('Open in new tab', $this->plugin_name); ?></span>
    </label>
</fieldset>
<span style="margin-left:10px; color:red; display: inline-block;" id="as-error-span"></span>
<p class="help">
    <?php _e('Specify a URL to redirect to after a visitor has succesfully opted in.', $this->plugin_name); ?>
</p>
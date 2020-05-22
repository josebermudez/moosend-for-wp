<?php 
    if ('admin_page_moosend-for-wp-edit-form' == get_current_screen()->id)
    {
        $current_screen = 'Edit';
    }

    $options = get_option('forms');
    $query_params = $this->ms4wp_get_query_params();
    $form = isset($query_params['form_id']) ? $options[$query_params['form_id']] : null;

    if (!empty($current_screen))
    {
        ?>
        <input type="text" class="regular-text" value="<?php _e($form->name); ?>" id="form-name" spellcheck="true" autocomplete="off" placeholder="Enter the name of your sign-up form" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter the name of your sign-up form'">
        <?php
    }else{
        ?>
        <input type="text" class="regular-text" id="form-name" spellcheck="true" autocomplete="off" placeholder="Enter the name of your sign-up form" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter the name of your sign-up form'">
        <?php
    }
?>
<p class="help">
    <?php _e( 'This is the internal name of your optin for easy reference.', $this->plugin_name ); ?>
</p>


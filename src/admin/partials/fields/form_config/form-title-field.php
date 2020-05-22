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
        <input type="text" class="regular-text" value="<?php _e($form->title); ?>" id="form-title" spellcheck="true" autocomplete="off" placeholder="Enter the title of your sign-up form" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter the title of your sign-up form'">
        <?php
    }else{
        ?>
        <input type="text" class="regular-text" id="form-title" spellcheck="true" autocomplete="off" placeholder="Enter the title of your sign-up form" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter the title of your sign-up form'">
        <?php
    }
?>
<p class="help">
    <?php _e( 'This is the title that the users will see along with your form.', $this->plugin_name ); ?>
</p>


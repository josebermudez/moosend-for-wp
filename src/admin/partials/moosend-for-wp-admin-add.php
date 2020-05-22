<?php 
/* 
* @link moosend.com 
* @since 1.0.0 
* 
* @package Moosend_For_Wp 
* @subpackage Moosend_For_Wp/admin/partials
*/
?>


<?php 

    if ( ! defined( 'WPINC' ) ) {
        die;
    }

    $existing_theme = null;
    $form = null;
    $page_mode = true;
    $options = get_option('forms');
    $query_params = $this->ms4wp_get_query_params();

    if(isset($query_params['form_id'])){
        $form = $options[$query_params['form_id']];
        $existing_theme = ($form->theme != null) ? $form->theme : null;
        $page_mode = ('moosend-for-wp_page_moosend-for-wp-add-form' == get_current_screen()->id);
    }

    if($this->get_mailing_lists($this->api_key) != null){
    ?>
    <div class="wrap">
    <p class="breadcrumbs">
    <a href="<?php echo admin_url( 'admin.php?page='.$this->plugin_name.'-general' ); ?>">Moosend for WordPress</a> &rsaquo;
    <span class="current-crumb"><strong>
    <?php
        $page_mode ? _e( 'Add Form', $this->plugin_name ) : _e( 'Edit Form '.$query_params['form_id'], $this->plugin_name );
    ?>
    </strong></span>

</p>
<hr>

<div class="page-title">
    <h1 class="ms4wp-title" style="font-weight: normal"><?php $page_mode ? _e( 'Add Form', $this->plugin_name ) : _e( 'Edit Form', $this->plugin_name ); ?></h1>
</div>


<div>
    <div class="col-7-12">
       <div class="content">
       <div class="notice notice-success is-dismissible" id="success-edit">
            <p class="help">
            <?php _e('You have successfully updated a form.', $this->plugin_name); ?>
            <a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_name.'-all-forms' ); ?>">
                <?php _e( 'See Your Subscription Forms Here.', $this->plugin_name ); ?>                
            </a>
            </p>
        </div>

        <div class="notice notice-success is-dismissible" id="success-new">
            <p class="help">
            <?php _e('You have successfully created a form.', $this->plugin_name); ?>
            <a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_name.'-all-forms' ); ?>">
                <?php _e( 'See Your Subscription Forms Here.', $this->plugin_name ); ?>                
            </a>
            </p>
        </div>
       <form id="form">
                <h2><?php esc_attr_e( 'Form Configuration', 'wp_admin_style' ); ?></h2>
                <section>
                    <?php 
                        settings_fields('form-group');
                        do_settings_sections($this->plugin_name.'-add-form-section');
                    ?>
                </section>
                        <h2><?php esc_attr_e( 'Custom Fields', 'wp_admin_style' ); ?></h2>
                <section>
                    <?php 
                        settings_fields('custom-fields-group');
                        do_settings_sections($this->plugin_name.'-custom-fields-section');
                    ?>
                </section>
                    <h2><?php esc_attr_e( 'Appearance', 'wp_admin_style' ); ?></h2>
                <section>
                    <table class="form-table">
                    <tbody id='theme-settings'>
                    <tr>
                    <th scope="row">Select Alignment</th>
                        <td>
                            <div>
                                <label for="theme-dropdown">
                                    <select id="theme-dropdown">
                                        <option value="inherit" <?php echo ($existing_theme == "inherit") ? "selected" : "" ?>>
                                        <?php  _e( 'Inherit from '.get_template().' theme', $this->plugin_name ); ?></option>
                                        <option value="basic" <?php echo ($existing_theme == "basic") ? "selected" : ""; ?>>Basic</option>
                                        <option value="valign" <?php echo ($existing_theme == "valign") ? "selected" : ""; ?>>Vertically Align</option>
                                    </select>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                    </tbody>
                    </table>
                    <div id="tabs">
                        <ul id="ms-form-tabs">
                            <li><a href="#tab-1">Title Styles</a></li>
                            <li><a href="#tab-2">Label Styles</a></li>
                            <li><a href="#tab-3">Field Styles</a></li>
                            <li><a href="#tab-4">Button Styles</a></li>
                        </ul>

                        <div id="tab-1">
                            <?php include_once('fields/tabs/title-settings-tab.php');
                                include('fields/tabs/field-template.php');
                        ?>
                        </div>
                        <div id="tab-2">
                            <?php include_once('fields/tabs/label-settings-tab.php');
                                include('fields/tabs/field-template.php');
                            ?>
                        </div>
                        <div id="tab-3">
                            <?php include_once('fields/tabs/field-settings-tab.php');
                            include('fields/tabs/field-template.php');
                        ?>
                        </div>
                        <div id="tab-4">
                            <?php include_once('fields/tabs/button-settings-tab.php');
                            include('fields/tabs/field-template.php');
                        ?>
                        </div>
                    </div>
                </section>
            </form>
       </div>
    </div>
    <div class="col-5-12">
       <div class="content">
       <div id="preview-container">
            <div id="preview" class="basic valign">    
            </div>
            </div>
       </div>
    </div>
</div>
    <?php

    }else{
    ?>

    <p class="help">
        <?php _e( 'Seems that you have not created any Mailing Lists.', $this->plugin_name ); ?>
        <a target="_blank" href="#" id="opens_window">
            <?php _e('You can create mailing lists here.', $this->plugin_name ); ?>     
        </a>
    </p>

    <div id="modal" class="form-table">
        <input type="text" placeholder="Your Login Domain" name="user-subdomain" id="user-subdomain"/>
        <span>.moosend.com</span>
        <input type="submit" id="modal-button" value="Get Me There" class="button moosend-orange-button">
    </div>
    <?php
    }
    ?>
</div>
<?php
    if ( ! defined( 'WPINC' ) ) {
        die;
    }

    //Create an instance of our package class...
    $testListTable = new Moosend_For_WP_List_Table2();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();

?>

<div class="wrap">
<p class="breadcrumbs">
        <a href="<?php echo admin_url( 'admin.php?page='.$this->plugin_name.'-general' ); ?>">Moosend for WordPress</a> &rsaquo;
        <span class="current-crumb"><strong><?php _e( 'All Forms', $this->plugin_name ); ?></strong></span>
    </span>
</p>
<hr>

    <div class="page-title">
        <h1 class="ms4wp-title" style="font-weight: normal"><?php _e("All Forms", $this->plugin_name) ?></h1>
    </div>

        <div id="icon-users" class="icon32"><br/></div>
        
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="post">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>
        </form>  
</div>

<script type="text/javascript">
    let deleteConfirmation = function(id, e){
        let response = confirm("Are you sure you want to delete the form with id=" + id);
        if(response == true){
            deleteForm(id);
        }else{
            e.preventDefault();
        }
    }

    let deleteForm = function(id){
        if(localStorage.getItem('formsDisplayed') !== null){
            let formsDisplayed = JSON.parse(localStorage.getItem('formsDisplayed'));
            delete formsDisplayed[id];
            localStorage.setItem('formsDisplayed', JSON.stringify(formsDisplayed));
        }
    }
</script>
<?php

if ( ! defined( 'WPINC' ) ) {
    die;
}

ob_start();

if(!class_exists('Moosend_WP_List_Table')){
    require_once(dirname(__DIR__).'/includes/class-moosend-wp-list-table.php');
}

class Moosend_For_WP_List_Table2 extends Moosend_WP_List_Table {


    function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'form',     //singular name of the listed records
            'plural'    => 'forms',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
            ));
    }

    function delete_form( $id )
    {
        $forms = get_option('forms');
        unset($forms[$id]);
        update_option('forms', $forms);
        echo '<script type="text/javascript">',
             'deleteForm('.$id.')',
             '</script>';
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'ID':
            return $item->id;
            case 'title': 
            return $item->name;
            case 'shortcode':
            return '<input type="text" style="background-color: inherit;" 
            onclick="this.select()" 
            readonly="readonly"  
            value="'.$item->shortcode.'"/>';
            case 'selected_list':
            return $item->selected_list;
            case 'custom_fields':
            return $item->custom_fields;
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
            }
        }

        function process_bulk_action() {

                //Detect when a bulk action is being triggered...
            if ( 'delete' === $this->current_action() ) {
                $nonce = esc_attr( $_REQUEST['_wpnonce'] );

                if (!wp_verify_nonce( $nonce, 'sp_delete_form')) {
                    die( 'Nonce verification error' );
                }else{
                  self::delete_form( absint( $_GET['form'] ) );
                }
            }

            if ((isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete')
            || (isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete')) 
            {
                $delete_ids = esc_sql( $_POST['forms'] );
                // loop over the array of record IDs and delete them
                foreach ( $delete_ids as $id ) {
                    self::delete_form( $id );
                }
            }
        }

function column_title($item){

    $delete_nonce = wp_create_nonce('sp_delete_form');

    //Build row actions
    $actions = array(
        'edit'      => sprintf('<a href="?page=%s&action=%s&form=%s">Edit</a>', 'moosend-for-wp-edit-form' ,'edit',$item->id),
        'delete'    => sprintf('<a onclick="deleteConfirmation(%s, event)" href="?page=%s&action=%s&form=%s&_wpnonce=%s">Delete</a>',
            absint($item->id),
            esc_attr( $_REQUEST['page'] ),
            'delete', 
            absint($item->id), 
            esc_attr($delete_nonce))
        );

    $temp_title = $item->name;
    $title = !empty($temp_title) ? $temp_title : 'Untitled';
    $title_url = sprintf('<a href="?page=%s&action=%s&form=%s">%s</a>', 'moosend-for-wp-edit-form' ,'edit', $item->id, $title);
        //Return the title contents
    return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $title_url, //$item['title'],
            /*$2%s*/ $item->id,
            /*$3%s*/ $this->row_actions($actions)
            );
}

function column_cb($item){
    return sprintf(
        '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['plural'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item->id           //The value of the checkbox should be the record's id
            );
}

function get_columns(){
    $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'title' => 'Title',
            'shortcode'  => 'Shortcode',
            'selected_list'  => 'Mailing List',
            );
    return $columns;
}

function get_sortable_columns() {
    $sortable_columns = array(
        'title'    => array('title',false),
        );
    return $sortable_columns;
}

function get_bulk_actions() {


    $actions = array(
        'bulk-delete'    => 'Delete'
        );
    return $actions;
}

function prepare_items() {

    $per_page = 5;

    $columns = $this->get_columns();
    $hidden = array();
    $sortable = $this->get_sortable_columns();

    $this->_column_headers = array($columns, $hidden, $sortable);

    $this->process_bulk_action();

    $data =  get_option('forms');     

    function usort_reorder($a,$b){
        $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; 
        $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; 
        $result = strcmp($a->name, $b->name); 
        return ($order==='asc') ? $result : -$result; 
    }
    usort($data, 'usort_reorder');

    $current_page = $this->get_pagenum();

    $total_items = count($data);

    $data = array_slice($data,(($current_page-1)*$per_page),$per_page);

    $this->items = $data;

    $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
            ) );
    } 
}

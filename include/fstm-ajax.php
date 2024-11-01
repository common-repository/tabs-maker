<?php 
//ajax function which add new tab
add_action('wp_ajax_fstm_add_new_tab', 'fstm_add_new_tab');
function fstm_add_new_tab()
{
    global $wpdb;
    $post_id = sanitize_key($_POST['post_id']);
    $tab_title = sanitize_text_field(strip_tags(trim($_POST['tab_title'])));
    $tab_ico = sanitize_text_field(strip_tags(trim($_POST['tab_ico'])));
    $tab_content = wp_kses_post($_POST['tab_content']);
    $tab_sort = sanitize_key($_POST['tab_sort']);
 
    $insert['tab_title'] = $tab_title;
    $insert['tab_ico'] = $tab_ico;
    $insert['tab_content'] = $tab_content;
    $insert['tab_sort'] = $tab_sort;
    
    fstm_tab_add_post_meta($post_id,'tabs',$insert);
    
    echo json_encode(array('meta_id' => $wpdb->insert_id));
    wp_die();
}	

//ajax function which get tab to edit
add_action('wp_ajax_fstm_get_tab_to_edit', 'fstm_get_tab_to_edit');
function fstm_get_tab_to_edit()
{
    global $wpdb;
    $post_id = sanitize_key($_POST['post_id']);
    $meta_id = sanitize_key($_POST['meta_id']);


    $tabs = fstm_tab_get_post_meta($post_id,'tabs');

    if($tabs) foreach($tabs as $tab){
        if($tab->meta_id == $meta_id){
        $tb_array = json_decode($tab->meta_value);
        $tb_array->tab_content = wp_specialchars_decode( stripslashes($tb_array->tab_content), ENT_QUOTES );
        $cur_tab = $tb_array;
        break;
    }
    }
    
    echo json_encode($cur_tab);
    wp_die();
}   

//ajax function which edit existing tab
add_action('wp_ajax_fstm_edit_tab', 'fstm_edit_tab');
function fstm_edit_tab()
{
    global $wpdb;

    $post_id = sanitize_key($_POST['post_id']);
    $tab_title = sanitize_text_field(strip_tags(trim($_POST['tab_title'])));
    $tab_ico = sanitize_text_field(strip_tags(trim($_POST['tab_ico'])));
    $tab_content = wp_kses_post($_POST['tab_content']);
    $tab_sort = sanitize_key($_POST['tab_sort']);
    $meta_id = sanitize_key($_POST['meta_id']);

    $insert['tab_title'] = $tab_title;
    $insert['tab_ico'] = $tab_ico;
    $insert['tab_content'] = $tab_content;
    $insert['tab_sort'] = $tab_sort;
    


    $update = fstm_tab_update_post_meta($meta_id,$insert);

    echo json_encode($update);
    wp_die();
}

//ajax function which sort all tabs
add_action('wp_ajax_fstm_order_all_tabs', 'fstm_order_all_tabs');
function fstm_order_all_tabs()
{
    global $wpdb;
    $post_id = sanitize_key($_POST['post_id']);
    $meta_ids = (isset($_POST['tabs_ids']) && is_array($_POST['tabs_ids']))?$_POST['tabs_ids']:wp_die();
    $sorts = (isset($_POST['sorts']) && is_array($_POST['sorts']))?$_POST['sorts']:wp_die();
    
    if($meta_ids) foreach($meta_ids as $key => $meta_id){
        $meta_value = fstm_tab_get_the_post_meta_value($meta_id);
        $meta_value = json_decode($meta_value);
        $meta_value->tab_content = wp_specialchars_decode( stripslashes($meta_value->tab_content), ENT_QUOTES );
        $meta_value->tab_sort = $sorts[$key];
        $update = fstm_tab_update_post_meta($meta_id,$meta_value);
 
    }
    
    echo json_encode(array($meta_ids));
    wp_die();
}

//ajax function which delete tab
add_action('wp_ajax_fstm_delete_tab', 'fstm_delete_tab');
function fstm_delete_tab()
{
    global $wpdb;
    $meta_id = sanitize_key($_POST['meta_id']);
    $res = fstm_tab_delete_post_meta($meta_id);
    
    echo json_encode(array('delete'=> 'ok'));
    wp_die();
}


//make alax preview
add_action('wp_ajax_fstm_make_preveiw_with_ajax', 'fstm_make_preveiw_with_ajax');
function fstm_make_preveiw_with_ajax()
{
    global $wpdb;
    $post_id = sanitize_key($_POST['post_id']);
    $tabs = fstm_tab_get_post_meta($post_id,'tabs');
    $fs_tab_skin = sanitize_text_field($_POST['tab_skin']);
    $ids = sanitize_key($_POST['ids']);
    //save ids meta
    $test_id = get_post_meta($post_id, 'ids', true);
    if(empty($test_id)) update_post_meta( $post_id, 'ids', $ids );
    $out_html = '';
    
if(count($tabs) > 0){
if($fs_tab_skin == 'horizontaltoptabs') $out_html .= fstm_tabs_horizontal($ids,'view');
elseif($fs_tab_skin == 'horizontalbottomtabs') $out_html .= fstm_tabs_vertical($ids,'view');
$out_html .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery(\'div[data-id='.$ids.']\').show();});</script>';
$out_code = fstm_tabs_out_in_shortcode($ids,$fs_tab_skin);

    $res['success'] = 'ok';
    $res['tabs'] = $out_html;
    $res['shortcode'] = $out_code;
}

    echo json_encode($res);
    wp_die();
}

?>
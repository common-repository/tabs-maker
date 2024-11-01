<?php 

// Main post type custom meta fields
add_action('add_meta_boxes', 'fstm_tabs_fields_custom_box');
function fstm_tabs_fields_custom_box($data){
	$screens = array( 'fs_tabs' );
	add_meta_box( 'fs_tabs_fields', __("Setting", 'fs_tabs' ), 'fstm_tabs_fields_callback', $screens, 'normal' );
}

// Sidebar post type custom meta fields
add_action('add_meta_boxes', 'fstm_tabs_sidebar_custom_box');
function fstm_tabs_sidebar_custom_box($data){
	$screens = array( 'fs_tabs' );
	add_meta_box( 'fs_tabs_sidebar_fields', __("Compatible plugins", 'fs_tabs' ), 'fstm_tabs_sidebar_fields_callback', $screens, 'side' );
}

// Results Tabs fields 
add_action('add_meta_boxes', 'fstm_tabs_results_custom_box');
function fstm_tabs_results_custom_box($data){
	$screens = array( 'fs_tabs' );
	add_meta_box( 'fs_tabs_results_fields', __("Results Tabs", 'fs_tabs' ), 'fstm_tabs_results_fields_callback', $screens, 'normal' );
}

// Results post type custom meta fields calback
function fstm_tabs_results_fields_callback( $post, $meta ){
	$new_tab = '';
	$tabs = fstm_tab_get_post_meta($post->ID,'tabs');

	//print_r(json_decode($tabs[0]));
	if(!empty($tabs)) {

	if($tabs) foreach($tabs as $tb){
		$tb_array = json_decode($tb->meta_value);
		$tb_array->meta_id = $tb->meta_id;
		$sort_tabs[$tb_array->tab_sort] = $tb_array;		
	}
	ksort($sort_tabs);

	if($sort_tabs) foreach($sort_tabs as $tb){
		$tb_array = $tb;
		$new_tab .= '<li class="fs-tabs-media-table-li" data-sort="'.$tb_array->tab_sort.'"><div class="fs-tabs-media-table-id">'.($tb_array->tab_sort+1).'</div><div class="fs-tabs-media-table-tabico"><i class="fa fa-'.$tb_array->tab_ico.' fa-2x"></i></div><div class="fs-tabs-media-table-tabtitle">'.$tb_array->tab_title.'</div><div class="fs-tabs-media-table-buttons"><a class="fs-tabs-media-table-button fs-tabs-media-table-edit" data-id="'.$tb_array->meta_id.'">Edit</a>&nbsp;|&nbsp;&nbsp;<a class="fs-tabs-media-table-button fs-tabs-media-table-delete" data-id="'.$tb_array->meta_id.'">Delete</a>&nbsp;|&nbsp;&nbsp;<a class="fs-tabs-media-table-button fs-tabs-media-table-moveup">Move Up</a>&nbsp;|&nbsp;&nbsp;<a class="fs-tabs-media-table-button fs-tabs-media-table-movedown">Move Down</a></div><div class="clear"></div></li>';
	}
}

?>
		<ul class="fs-tabs-table" id="fs-tabs-media-table" <?php if(count($tabs) > 0) echo 'style="display:block !important;"';?>><?php echo $new_tab;?></ul>
<?php
}

// Sidebar post type custom meta fields calback
function fstm_tabs_sidebar_fields_callback( $post, $meta ){

?>
<div class="fs_info"><a href="https://www.fla-shop.com/wordpressmaps.php" target="_blank"><img src="<?php echo plugins_url('tabs-maker/images/image_508_1250.png'); ?>" alt="interactive maps" width="254" height="625"></a></div>
<?php
}

// Main post type custom meta fields calback
function fstm_tabs_fields_callback( $post, $meta ){
	global $wpdb;
	$ids = get_post_meta($post->ID,'ids',true);
	$tabs = fstm_tab_get_post_meta($post->ID,'tabs');

	$fs_tab_skin = get_post_meta($post->ID,'fs_tab_skin',true);
	if(empty($fs_tab_skin)) $fs_tab_skin = 'horizontaltoptabs';

	
	$fs_tab_mobil = get_post_meta($post->ID,'fs_tab_mobil',true);
	if(empty($ids)){
	$max_ids = $wpdb->get_var( "SELECT MAX(CAST(meta_value AS UNSIGNED)) FROM " . $wpdb->prefix . "postmeta as pm
        JOIN " . $wpdb->prefix . "posts as p ON p.ID = pm.post_id AND p.post_type = 'fs_tabs'
        WHERE meta_key = 'ids'" );
	$ids = $max_ids+1;
	}
	$screens = $meta['args'];

	wp_nonce_field( plugin_basename(__FILE__), 'fs_tabs_fields' );

	?>
	<?php if(isset($_GET['tab']) and $_GET['tab'] == 'view'):?>
		<script>
			jQuery(document).ready(function($){
					$('#fs_load_tabs').empty();
					$('#fs_load_tabs').append('<center>'+translation.fs_loading_text+'</center>');
					$('.fs_section h2.nav-tab-wrapper a').removeClass('nav-tab-active');
					$('.fs_section h2.nav-tab-wrapper a:last').addClass('nav-tab-active');
					$('.fs_section .box').hide();
					$('.fs_section .box:last').show();
					$('#fs_tabs_results_fields').hide();

				var data = {
            action: "fstm_make_preveiw_with_ajax",
            post_id: $('input[name=post_ID]').val(),
            ids: $('input[name=fs_ids]').val(),
            tab_skin: $('input[name=fs_tab_skin]:checked').val()
        };

            $.post(ajaxurl, data, function(response){
                
                    if(response && response.success){
                    $('#fs_load_tabs').empty();
                    $('#fs_load_tabs').append(response.tabs);
                    $('#fs_tabs_prev_shortcode').val(response.shortcode);

                } else {
                $('#fs_load_tabs').empty();
                var not_yet = '<center>'+translation.fs_no_tab_yet_text+'</center>';
                $('#fs_load_tabs').append(not_yet);
                $('#fs_tabs_prev_shortcode').val('');
                }
            },'json');
		});
		</script>
	<?php endif;?>
	<input type="hidden" name="fs_ids" value="<?php echo $ids;?>">
	<div class="fs_section">
   	<h2 class="nav-tab-wrapper">
    	<a id="fs_main_tab" onfocus="this.blur();" href="javascript:" class="nav-tab nav-tab-active"><?php echo __( 'Tabs', 'fs_tabs' ); ?></a>
    	<a href="javascript:" onfocus="this.blur();" class="nav-tab "><?php echo __( 'Skins', 'fs_tabs' ); ?></a>
    	<a href="javascript:" onfocus="this.blur();" class="nav-tab "><?php echo __( 'Option', 'fs_tabs' ); ?></a>
    	<a id="fs_preview_tab" onfocus="this.blur();" href="javascript:" class="nav-tab "><?php echo __( 'Preview', 'fs_tabs' ); ?></a>
		</h2>

    <div class="box visible">

      <input type="button" class="button button-primary button-large" value="<?php echo __("Add", 'fs_tabs' ); ?>" id="fs_add">

   <div class="fs_add_section"> 
     	<div class="row"> 
      	<label for="fs_tab_title"><?php echo __("Tab Title", 'fs_tabs' ); ?> <span style="color:red;">*</span></label>
				<input type="text" id="fs_tab_title" name="fs_tab_title" placeholder="<?php echo __("Enter Tab Title", 'fs_tabs' ); ?>" value=""/>
			</div>

			<div class="row"> 
      	<label for="fs_tab_ico"><?php echo __("Tab Icon", 'fs_tabs' ); ?></label>
				<input type="text" id="fs_tab_ico" name="fs_tab_ico" placeholder="<?php echo __("Choose Tab Icon", 'fs_tabs' ); ?>" value=""/>
				<p class="small">Font Awesome CSS Class Example: <b>info-circle</b> <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_blank">View The Complete Font Awesome Icon List</a></p>
			</div>

			<div class="row"> 
				<?php
					if (!isset($fs_tab_description_value))
						$fs_tab_description_value = '';
    				wp_editor( htmlspecialchars_decode($fs_tab_description_value), 'fs_tab_description',array('textarea_name'=>'fs_tab_description') );
?>		</div>
			<div class="row">
				<input type="button" class="button button-primary button-large" value="<?php echo __("Add Tab", 'fs_tabs' ); ?>" id="fs_add_tab">				
				<input type="button" class="button button-primary button-large" value="<?php echo __("Edit", 'fs_tabs' ); ?>" id="fs_edit_tab">
				<input type="button" class="button button-large" value="<?php echo __("Cancel", 'fs_tabs' ); ?>" id="fs_add_cancel">				
				<span class="spinner"></span>
			</div>
		</div>

    </div>
    <div class="box">
        			<div class="fs-tab-skin">
								<label><input type="radio" name="fs_tab_skin" <?php if($fs_tab_skin == 'horizontaltoptabs') echo 'checked';?> value="horizontaltoptabs"><?php echo __("Horizontal Tabs", 'fs_tabs' ); ?><br /><img <?php if($fs_tab_skin == 'horizontaltoptabs') echo 'class="selected"';?> src="<?php echo plugins_url('tabs-maker/images/horizontaltoptabs.png'); ?>" /></label>
							</div>
							<div class="fs-tab-skin">
								<label><input type="radio" name="fs_tab_skin" <?php if($fs_tab_skin == 'horizontalbottomtabs') echo 'checked';?> value="horizontalbottomtabs"><?php echo __("Vertical Tabs", 'fs_tabs' ); ?><br /><img <?php if($fs_tab_skin == 'horizontalbottomtabs') echo 'class="selected"';?> src="<?php echo plugins_url('tabs-maker/images/verticallefttabs.png'); ?>" /></label>
							</div>
							<div class="clear"></div>
    </div>
    <div class="box">
        <div class="row"> 
      	<label for="fs_tab_mobil"><?php echo __("Mobile width", 'fs_tabs' ); ?></label>
				<input type="text" id="fs_tab_mobil" name="fs_tab_mobil" placeholder="<?php echo __("Enter mobile width, like example 500", 'fs_tabs' ); ?>" value="<?php echo $fs_tab_mobil;?>"/>
			</div>  
    </div>
    <div class="box">
        <div class="fs_block">
        		<h3 class="fs_block_h3"><?php echo __("Shortcode", 'fs_tabs' ); ?></h3>
        		<p class="fs_block_shortcod">[fs_tab id="<?php echo $ids;?>"]</p>
        </div> 
        <div class="fs_block">
        		<h3 class="fs_block_h3"><?php echo __("Tab Preview", 'fs_tabs' ); ?></h3>
        		<p id="fs_load_tabs"></p>
        </div>
        <div class="fs_block">
        		<h3 class="fs_block_h3"><?php echo __("Tab Preview in Shortcode", 'fs_tabs' ); ?></h3>
        		<p><textarea name="fs_tabs_prev_shortcode" id="fs_tabs_prev_shortcode"></textarea></p>
        </div>
    </div>
</div>
	<?php
}

//save custom post tabs fields
add_action( 'save_post', 'fstm_tabs_save_postdata' );
function fstm_tabs_save_postdata( $post_id ) {

	if ( ! wp_verify_nonce( isset($_POST['fs_tabs_fields']) ? $_POST['fs_tabs_fields'] : '', plugin_basename(__FILE__) ) )
		return;

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return;

	if( ! current_user_can( 'edit_post', $post_id ) )
		return;

	$fs_ids = sanitize_text_field( $_POST['fs_ids'] );
	$fs_tab_mobil = sanitize_text_field( $_POST['fs_tab_mobil'] );
	$fs_tab_skin = sanitize_text_field( $_POST['fs_tab_skin'] );


	update_post_meta( $post_id, 'ids', $fs_ids );
	update_post_meta( $post_id, 'fs_tab_mobil', $fs_tab_mobil );
	update_post_meta( $post_id, 'fs_tab_skin', $fs_tab_skin );
}
?>

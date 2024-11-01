<?php

function fstm_tabs_prepare_sorted_tabs($tab_id){
	$post_id = fstm_tab_get_post_id_by_meta_value($tab_id);
	$tabs = fstm_tab_get_post_meta($post_id,'tabs');

	if(empty($tabs)) return array();

	if($tabs) foreach($tabs as $tb){
		$tb_array = json_decode($tb->meta_value);
		$tb_array->meta_id = $tb->meta_id;
		$tb_array->tab_content = wp_specialchars_decode( stripslashes($tb_array->tab_content), ENT_QUOTES );
		$sort_tabs[$tb_array->tab_sort] = $tb_array;		
	}
	ksort($sort_tabs);
	return $sort_tabs;
}
//make horizontal tab from $tab_id 
function fstm_tabs_horizontal($tab_id,$mode=''){

	$sort_tabs = fstm_tabs_prepare_sorted_tabs($tab_id);

$out = '';
$out .= '<div class="fs_hor_type_tabs" id="fs_tab" data-id="'.$tab_id.'">';
$out .= '<ul class="fs_tabs_nav">';
foreach($sort_tabs as $k => $t){
if($k == '0') $active = ' class="active"';
else $active = '';
$out .= '<li'.$active.'><a href="javascript:">';
if(!empty($t->tab_ico)) $out .= '<i class="fa fa-'.$t->tab_ico.' fa-1x"></i>';
$out .=$t->tab_title.'</a></li>';
}
$out .= '</ul>';
$out .= '<div class="front_fs_tabs_content">';
foreach($sort_tabs as $k => $t){
if($k == '0') $active = ' active';
else $active = '';
$out .= '<div class="fs_tab_content'.$active.'">';
if($mode == 'view') $out .= $t->tab_content;
else $out .= apply_filters('the_content',$t->tab_content);
$out .= '<div class="clear"></div>';
$out .= '</div>';
}
$out .= '</div>';
$out .= '</div><div class="clear"></div>';

if($mode == 'view') $out .= '<p><i>'.__("If you use shortcodes of third-party plugins inside Tabs, please test it on the page or in the post.", 'fs_tabs' ).'</i></p>';
return $out;
}


//make vertical tab from $tab_id 
function fstm_tabs_vertical($tab_id,$mode=''){
	$sort_tabs = fstm_tabs_prepare_sorted_tabs($tab_id);

$out = '';
$out .= '<div class="fs_vert_type_tabs" id="fs_tab" data-id="'.$tab_id.'">';
$out .= '<ul class="fs_tabs_nav">';
foreach($sort_tabs as $k => $t){
if($k == '0') $active = ' class="active"';
else $active = '';
$out .= '<li'.$active.'><a href="javascript:">';
if(!empty($t->tab_ico)) $out .= '<i class="fa fa-'.$t->tab_ico.' fa-1x"></i>';
$out .=$t->tab_title.'</a></li>';
}
$out .= '</ul>';
$out .= '<div class="front_fs_tabs_content">';
foreach($sort_tabs as $k => $t){
if($k == '0') $active = ' active';
else $active = '';
$out .= '<div class="fs_tab_content'.$active.'">';
if($mode == 'view') $out .= $t->tab_content;
else $out .= apply_filters('the_content',$t->tab_content);
$out .= '<div class="clear"></div>';
$out .= '</div>';
}
$out .= '</div>';
$out .= '</div><div class="clear"></div>';

if($mode == 'view') $out .= '<p><i>'.__("If you use shortcodes of third-party plugins inside Tabs, please test it on the page or in the post.", 'fs_tabs' ).'</i></p>';

return $out;
}


//make tab from shortcode in preview tabs in admin post edit
function fstm_tabs_out_in_shortcode($tab_id,$tab_skin = ''){
	$post_id = fstm_tab_get_post_id_by_meta_value($tab_id);
	$tabs = fstm_tab_get_post_meta($post_id,'tabs');
	$fs_tab_skin = $tab_skin;
	if(empty($tab_skin)) $fs_tab_skin = get_post_meta($post_id,'fs_tab_skin',true);
	if($fs_tab_skin == 'horizontaltoptabs') $type = 'hor';
	else if($fs_tab_skin == 'horizontalbottomtabs') $type = 'vert';

if(empty($tabs)) return;

	if($tabs) foreach($tabs as $tb){
		$tb_array = json_decode($tb->meta_value);
		$tb_array->meta_id = $tb->meta_id;
		$tb_array->tab_content = wp_specialchars_decode( stripslashes($tb_array->tab_content), ENT_QUOTES );
		$sort_tabs[$tb_array->tab_sort] = $tb_array;		
	}
	ksort($sort_tabs);


$out = '';
$out .= '[fs_tab type="'.$type.'"]'."\n";
foreach($sort_tabs as $k => $t){
$out .= '[fs_tab_title ico="'.$t->tab_ico.'"]'.$t->tab_title.'[/fs_tab_title]'."\n";
}
foreach($sort_tabs as $k => $t){
$out .= '[fs_tab_content]'."\n";
$out .= $t->tab_content."\n";;
$out .= '[/fs_tab_content]'."\n";
}
$out .= '[/fs_tab]';

return $out;
}

//make tab from shortcode parse $content
function fstm_tabs_make_from_shortcode($data){

$out_array = array();

if($data) foreach($data as $key => $dt){
$type = (key($dt));
$dt = reset($dt);


$out = '';
$out .= '<div class="fs_'.$type.'_type_tabs" data-type="'.$type.'" id="fs_tab">';
$out .= '<ul class="fs_tabs_nav">';
foreach($dt['tabs_titles']['title'] as $k => $t){
if($k == '0') $active = ' class="active"';
else $active = '';
$out .= '<li'.$active.'><a href="javascript:">';
if(!empty($dt['tabs_titles']['ico'][$k])) $out .= '<i class="fa fa-'.$dt['tabs_titles']['ico'][$k].' fa-1x"></i>';
$out .=$t.'</a></li>';
}
$out .= '</ul>';
$out .= '<div class="front_fs_tabs_content">';
foreach($dt['tabs_content'] as $k => $t){
if($k == '0') $active = ' active';
else $active = '';
$out .= '<div class="fs_tab_content'.$active.'">';
$out .= apply_filters('the_content',$t);;
$out .= '<div class="clear"></div>';
$out .= '</div>';
}
$out .= '</div>';
$out .= '</div><div class="clear"></div>';
$out_array[$key.'_'.$type] = $out;
}

return $out_array;
}
?>

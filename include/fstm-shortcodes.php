<?php 
//main hook for parse $content. This function find any tab shortcode and return HTML
add_filter( 'the_content', 'fstm_make_tab_from_shortcode');
function fstm_make_tab_from_shortcode( $content ) {

global $post,$content_out;

preg_match_all("/\[fs\_tab.*?id=&.*?\;(.*?)&.*?\;\]/uis",$content, $get_tabs_with_id);
$out_html = array();
if(!empty($get_tabs_with_id['1'])){
foreach($get_tabs_with_id['1'] as $tab_id){
$post_id = fstm_tab_get_post_id_by_meta_value($tab_id);
//if tab post_status not publish then continue
$tab_data = get_post($post_id);
if($tab_data->post_status != 'publish') continue;
$fs_tab_skin = get_post_meta($post_id,'fs_tab_skin',true);
$fs_mobil = get_post_meta($post_id,'fs_tab_mobil',true);

$out_responsive = '';
if(!empty($fs_mobil)){
$out_responsive = '<script type="text/javascript">';
$out_responsive .= 'jQuery(document).ready(function() {';
$out_responsive .= 'if(jQuery(\'body\').width() < '.$fs_mobil.') {';
$out_responsive .= 'jQuery(\'div[data-id='.$tab_id.']\').addClass(\'fs_mobi\');';
$out_responsive .= '} else {';
$out_responsive .= 'jQuery(\'div[data-id='.$tab_id.']\').removeClass(\'fs_mobi\');';
$out_responsive .= '}';
$out_responsive .= '});';
$out_responsive .= 'jQuery(window).resize(function(){';
$out_responsive .= 'if(jQuery(\'body\').width() < '.$fs_mobil.') {';
$out_responsive .= 'jQuery(\'div[data-id='.$tab_id.']\').addClass(\'fs_mobi\');';
$out_responsive .= '} else {';
//$out_responsive .= 'jQuery(\'div[data-id='.$tab_id.'] .js_temp_content\').remove();';
$out_responsive .= 'jQuery(\'div[data-id='.$tab_id.']\').removeClass(\'fs_mobi\');';
$out_responsive .= '}';
$out_responsive .= '});';
$out_responsive .= '</script>';
}
$out_responsive .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery(\'div[data-id='.$tab_id.']\').show();});</script>';

if($fs_tab_skin == 'horizontaltoptabs') $out_html[] = fstm_tabs_horizontal($tab_id).$out_responsive;
elseif($fs_tab_skin == 'horizontalbottomtabs') $out_html[] = fstm_tabs_vertical($tab_id).$out_responsive;


}

if($out_html) foreach($out_html as $html){
$content = preg_replace("/\[fs\_tab.*?id\=&.*?\;(.*?)&.*?\;\]/u", $html, $content, 1);
}

}

preg_match_all("/\[fs\_tab.*?type\=&.*?\;(.*?)&.*?\;\](.*?)\[\/fs\_tab\]/uis",$content, $get_tabs);


if(!empty($get_tabs['2'])){
$i=1;
$res = '';
foreach($get_tabs['2'] as $key => $it){

$type = $get_tabs['1'][$key];
$get_tabs_titles = '';
$get_tabs_content = '';

preg_match_all("/\[fs\_tab\_title.*?ico\=&.*?\;(.*?)&.*?\;.*?\](.*?)\[\/fs\_tab\_title\]/uis",$it, $get_tabs_titles);
preg_match_all("/\[fs\_tab\_title.*?\](.*?)\[\/fs\_tab\_title\]/uis",$it, $get_tabs_titles_no_ico);
preg_match_all("/\[fs\_tab\_content\](<\/p>|)(.*?)\[\/fs\_tab\_content\]/uis",$it, $get_tabs_content);

$res[$i][$type]['tabs_titles']['ico'] = $get_tabs_titles['1'];
$res[$i][$type]['tabs_titles']['title'] = $get_tabs_titles['2'];
$res[$i][$type]['tabs_titles']['title'] = $get_tabs_titles_no_ico['1'];
$res[$i][$type]['tabs_content'] = $get_tabs_content['2'];
$i++;}

$out_html = fstm_tabs_make_from_shortcode($res);


if($out_html) foreach($out_html as $type => $html){
$type = explode('_',$type);
$type = $type['1'];
$html .= '<script type="text/javascript">jQuery(document).ready(function() {jQuery(\'div[data-type='.$type.']\').show();});</script>';
$content = preg_replace("/\[fs\_tab.*?type\=&.*?\;$type&.*?\;\](.*?)\[\/fs\_tab\]/uis", $html, $content, 1);
$html = '';
}



}
return $content;
}

//specific function for use tab shortcode in any page or post place
function fstm_do_shortcode($shortcode){
	if(empty($shortcode)) return __("No tab to output", 'fs_tabs' );

preg_match_all("/\[fs\_tab.*?id\=\"(.*?)\"\]/u",$shortcode, $get_tabs_with_id);



if(!empty($get_tabs_with_id['1'])){

foreach($get_tabs_with_id['1'] as $tab_id){
$post_id = fstm_tab_get_post_id_by_meta_value($tab_id);

//if tab post_status not publish then continue
$tab_data = get_post($post_id);

if($tab_data->post_status != 'publish') continue;
$fs_tab_skin = get_post_meta($post_id,'fs_tab_skin',true);

$show_js = '<script type="text/javascript">jQuery(document).ready(function() {jQuery(\'div[data-id='.$tab_id.']\').show();});</script>';

if($fs_tab_skin == 'horizontaltoptabs') $out_html[$tab_id] = fstm_tabs_horizontal($tab_id);
elseif($fs_tab_skin == 'horizontalbottomtabs') $out_html[$tab_id] = fstm_tabs_vertical($tab_id);

$out_html[$tab_id] .= $show_js;
}

if($out_html) return reset($out_html);
}
return __("No tab to output", 'fs_tabs' );
}

?>

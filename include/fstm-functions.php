<?php 
//function get post meta_value by $meta_id
function fstm_tab_get_the_post_meta_value($meta_id){
	global $wpdb;
	$meta_id = esc_sql($meta_id);
	return $wpdb->get_var( "SELECT meta_value FROM " . $wpdb->prefix . "postmeta WHERE meta_id = '$meta_id'");
}

//function get post meta by $post_id and $key
function fstm_tab_get_post_meta($post_id,$key){
	global $wpdb;
	$key = esc_sql($key);
	$post_id = esc_sql($post_id);
	return $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '$key' AND post_id = '$post_id'");
}

//function delete post meta by $meta_id
function fstm_tab_delete_post_meta($meta_id){
	global $wpdb;
	$meta_id = esc_sql($meta_id);
	return $wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "postmeta WHERE meta_id = %s", $meta_id) );
}

//function add post meta by $post_id
function fstm_tab_add_post_meta($post_id,$meta_key,$meta_value){
	global $wpdb;
	$post_id = esc_sql($post_id);
	$meta_key = esc_sql($meta_key);
	$meta_value = json_encode($meta_value);
	return $wpdb->query( $wpdb->prepare("INSERT INTO " . $wpdb->prefix ."postmeta (post_id, meta_key, meta_value) VALUES (%s, %s, %s)", $post_id, $meta_key, $meta_value) );
}

//function update post meta by $post_id
function fstm_tab_update_post_meta($meta_id,$meta_value){
	global $wpdb;
	$meta_id = esc_sql($meta_id);
	$meta_value = json_encode($meta_value);
	return $wpdb->query( $wpdb->prepare ("UPDATE " . $wpdb->prefix . "postmeta SET meta_value= '%s' WHERE meta_key = 'tabs' AND meta_id = %s", $meta_value, $meta_id) );
}

//function get post_id by meta_value $ids
function fstm_tab_get_post_id_by_meta_value($ids){
	global $wpdb;
	$ids = esc_sql($ids);
	return $wpdb->get_var( "SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key = 'ids' AND meta_value = '$ids'");
}

?>
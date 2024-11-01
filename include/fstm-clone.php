<?php 
	//main function to add new clone
add_action('post_action_clone','fstm_tabs_clone_action');
function fstm_tabs_clone_action($post_id){
	global $post,$wpdb;

	//get post tab main meta
	$max_ids = $wpdb->get_var( "SELECT MAX(CAST(meta_value AS UNSIGNED)) FROM " . $wpdb->prefix . "postmeta as pm
        JOIN " . $wpdb->prefix . "posts as p ON p.ID = pm.post_id AND p.post_type = 'fs_tabs'
        WHERE meta_key = 'ids'" );
	$ids = $max_ids+1;

	$fs_tab_mobil = get_post_meta( $post_id, 'fs_tab_mobil', true );
	$fs_tab_skin = get_post_meta( $post_id, 'fs_tab_skin', true );

	//get post tab all include tabs

	$tabs = fstm_tab_get_post_meta($post_id,'tabs');

	//add clone post tab

	$post_data = array(
		'post_title'    => wp_strip_all_tags( $post->post_title.' - '.__("Clone", 'fs_tabs' ) ),
		'post_content'  => '',
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_type'   => 'fs_tabs'
	);

	$clone_post_id = wp_insert_post( $post_data );

	add_post_meta( $clone_post_id, 'ids', $ids );
	add_post_meta( $clone_post_id, 'fs_tab_mobil', $fs_tab_mobil );
	add_post_meta( $clone_post_id, 'fs_tab_skin', $fs_tab_skin );

	if($tabs) foreach($tabs as $tab){
				$meta_value = json_decode($tab->meta_value);
        fstm_tab_add_post_meta($clone_post_id,'tabs',$meta_value);
	}

	wp_redirect( add_query_arg( array('post_type' => $post->post_type), admin_url('edit.php') ) );
	exit;
}
?>

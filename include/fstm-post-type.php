<?php 

//add new custom post type fs_tabs
function fstm_tabs_custom_post_type(){

    $labels = array(
        "name" => __( 'Manage tab sets', 'fs_tabs' ),
        "singular_name" => __( 'Manage tab set', 'fs_tabs' ),
        "menu_name" => __( 'Tabs Maker', 'fs_tabs' ),
        "all_items" => __( 'Manage tab sets', 'fs_tabs' ),
        "add_new" => __( 'Add new tab set', 'fs_tabs' ),
        "add_new_item" => __( 'Add new tab set', 'fs_tabs' ),
        "edit_item" => __( 'Edit new tab set', 'fs_tabs' ),
        "new_item" => __( 'New tab set', 'fs_tabs' ),
        "search_items" => __( 'Search Tabs', 'fs_tabs' ),
        "not_found" => __( 'Tabs not found', 'fs_tabs' ),
    );

    $args = array(
        "label" => __( 'Tabs Maker', 'fs_tabs' ),
        "labels" => $labels,
        "description" => "",
        "public" => false,
        "publicly_queryable" => false,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "fs_tabs", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 90,
        'supports'=> array('title'),
        'menu_icon'=> 'dashicons-feedback'
    );

    register_post_type( "fs_tabs", $args );
}

add_action( 'init', 'fstm_tabs_custom_post_type' );


//add new columns for post type fs_tabs
function fstm_tabs_list_columns()
{

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'ids' => __( 'ID', 'fs_tabs' ),
        'title' => __( 'Title', 'fs_tabs' ),
        'shortcode' => __( 'Shortcode', 'fs_tabs' ),
        'function' => __( 'PHP code', 'fs_tabs' ),
    );

    return $columns;
}

add_filter('manage_fs_tabs_posts_columns' , 'fstm_tabs_list_columns');

//edit existing post row actions for post type fs_tabs
add_filter('post_row_actions', 'fstm_tabs_removetrashlink', 10, 2);
function fstm_tabs_removetrashlink($actions, $post)
{
    if ($post->post_type == 'fs_tabs') {
        unset($actions['inline hide-if-no-js']);

        $edit = $actions['edit'];
        $trash = isset($actions['trash']) ? $actions['trash'] : null;
        $untrash = isset($actions['untrash']) ? $actions['untrash'] : null;
        $delete = isset($actions['delete']) ? $actions['delete'] : null;

        $link_preview = add_query_arg(
            array(
                'post' => $post->ID, // as defined in the hidden page
                'action' => 'edit',
                'tab' => 'view'
            ),
            admin_url('post.php')
        );

        $link_clone = add_query_arg(
            array(
                'post' => $post->ID, // as defined in the hidden page
                'action' => 'clone'
            ),
            admin_url('post.php')
        );

        $actions = array();
        $actions['edit'] = $edit;
        $actions['clone-action'] = '<a href="' . $link_clone . '">'.__( 'Clone', 'fs_tabs' ).'</a>';
        $actions['preview-action'] = '<a href="' . $link_preview . '">'.__( 'Preview', 'fs_tabs' ).'</a>';
        $actions['trash'] = $trash;

        if(!empty($untrash)){
        	$actions = array();
        	$actions['untrash']= $untrash;
        	$actions['delete']= $delete;

        }
    }

    return $actions;
}

//add new sort columns for post type fs_tabs
add_filter( 'manage_edit-fs_tabs_sortable_columns', 'register_fstm_tabs_column_sortable' );
function register_fstm_tabs_column_sortable( $newcolumn ) {
    $newcolumn['ids'] = 'ids';
    return $newcolumn;
}


//add new query set for new sort columns for post type fs_tabs
add_action( 'pre_get_posts', 'fstm_manage_wp_posts_be_qe_pre_get_posts', 1 );
function fstm_manage_wp_posts_be_qe_pre_get_posts( $query ) {

      $order = strtoupper( $query->get( 'order' ) );

       if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) )
         $order = 'ASC';
$orderby = $query->get( 'orderby' );

if($query->is_main_query() && isset($query->query['post_type']) && $query->query['post_type'] == 'fs_tabs' && empty($orderby)){

            $query->set( 'meta_key', 'ids' );
            $query->set( 'orderby', 'CAST(meta_value as UNSIGNED)' );
            $query->set( 'order', $order );
}
   elseif ( $query->is_main_query() && isset($query->query['post_type']) && $query->query['post_type'] == 'fs_tabs' && !empty($orderby) ) {
      switch( $orderby ) {
         case 'ids':
            $query->set( 'meta_key', 'ids' );
            $query->set( 'orderby', 'CAST(meta_value as UNSIGNED)' );
            $query->set( 'order', $order );
            break;
      }

   }

}

//view columns for post type fs_tabs
add_action('manage_fs_tabs_posts_custom_column', 'fstm_tabs_posts_custom_column', 10, 2);
function fstm_tabs_posts_custom_column($column, $postId)
{
$ids = get_post_meta($postId,'ids',true);
	switch ($column) {
        case 'ids':
            echo $ids;
            break;
        case 'shortcode':
            echo '[fs_tab id="'.$ids.'"]';
            break;
        case 'function':
            echo esc_html('<?php echo fstm_do_shortcode(\'[fs_tab id="'.$ids.'"]\'); ?>');
            break;
          }
}
?>

<?php 
// Add CSS
add_action('admin_enqueue_scripts', 'fstm_style');
add_action('wp_enqueue_scripts', 'fstm_style_frontend');
function fstm_style()
{
    wp_enqueue_style('fstm_style', plugin_dir_url( dirname(__FILE__) ) . 'css/fs_style.css');
}

function fstm_style_frontend()
{
    wp_enqueue_style('fstm_style_frontend', plugin_dir_url( dirname(__FILE__) ) . 'css/fs_style_frontend.css');
}

// Add font-awesome CSS
add_action('admin_enqueue_scripts', 'fstm_font_awesome_style');
add_action('wp_enqueue_scripts', 'fstm_font_awesome_style_frontend',99 );
function fstm_font_awesome_style()
{
    wp_enqueue_style('fstm_font_awesome_style', plugin_dir_url( dirname(__FILE__) ) . 'css/font-awesome/css/fontawesome-all.css');
}

function fstm_font_awesome_style_frontend()
{   
    wp_enqueue_style('fstm_font_awesome_style', plugin_dir_url( dirname(__FILE__) ) . 'css/font-awesome/css/fontawesome-all.css');
}


// Set localized translation strings for JS files
global $fstm_tabs_js_translations;  
  $fstm_tabs_js_translations = array(
            'fs_error_title_tab' => __( 'Title of tab cann\'t be empty', 'fs_tabs' ),
            'fs_tab_delete_text' => __( 'Are you sure you want to delete Tab?', 'fs_tabs' ),
            'fs_no_tab_yet_text' => __("No Tab to Preview now", 'fs_tabs' ),
            'fs_loading_text' => __("loading, please wait ...", 'fs_tabs' ),
            );

// Add Scripts
add_action('admin_enqueue_scripts', 'fstm_scripts');
add_action('wp_enqueue_scripts', 'fstm_scripts_frontend');
function fstm_scripts()
{
global $fstm_tabs_js_translations;
    wp_register_script('fstm_scripts', plugin_dir_url( dirname(__FILE__) ) . 'js/fs_scripts.js', array('jquery'), false, true);

    // Register Localized Scripts
    wp_localize_script( 'fstm_scripts', 'translation', $fstm_tabs_js_translations );

    // Enqueue Scripts
    wp_enqueue_script( 'fstm_scripts' );
}

function fstm_scripts_frontend()
{
global $fstm_tabs_js_translations;
    wp_register_script('fstm_scripts_frontend', plugin_dir_url( dirname(__FILE__) ) . 'js/fs_scripts_frontend.js', array('jquery'), false, true);

    // Register Localized Scripts
    wp_localize_script( 'fstm_scripts_frontend', 'translation', $fstm_tabs_js_translations );

    // Enqueue Scripts
    wp_enqueue_script( 'fstm_scripts_frontend' );

}
?>

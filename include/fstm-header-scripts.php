<?php 
//main admin footer js script
add_action('admin_footer', 'fstm_custom_bulk_admin_footer');
function fstm_custom_bulk_admin_footer() {

  global $post_type,$post;

  if($post_type == 'fs_tabs') {
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery("select[name='action'] .hide-if-no-js").remove();
        jQuery("select[name='action2'] .hide-if-no-js").remove();

    if(jQuery('body').width() < 450<?php $fs_tab_mobil = get_post_meta($_GET['post'],'fs_tab_mobil',true); if(!empty($fs_tab_mobil)):?> || jQuery('body').width() < <?php echo $fs_tab_mobil; endif;?>) { 
   		jQuery('#fs_tab').addClass('fs_mobi');
    } else {
    	jQuery('#fs_tab').removeClass('fs_mobi');
    }

      jQuery('#publish').click(function(){
  if(jQuery('.fs_add_section').css('display') == 'block'){
  if (confirm("<?php echo __("Form for adding new Tab exist not saving data, continue?", 'fs_tabs' ); ?>")) {
    return true;
  } else {
    return false;
  }
}
 });

      });
     jQuery(window).resize(function(){
     if(jQuery('body').width() < 450<?php $fs_tab_mobil = get_post_meta($_GET['post'],'fs_tab_mobil',true); if(!empty($fs_tab_mobil)):?> || jQuery('body').width() < <?php echo $fs_tab_mobil; endif;?>) { 
   		jQuery('#fs_tab').addClass('fs_mobi');
    } else {
    	jQuery('#fs_tab .js_temp_content').remove();
    	jQuery('#fs_tab').removeClass('fs_mobi');
    }
      jQuery('body #fs_tab').each(function(){
          jQuery(this).find('.fs_tabs_nav li').removeClass('active'); 
          jQuery(this).find('.fs_tabs_nav li:first').addClass('active'); 
          jQuery(this).find('.front_fs_tabs_content .fs_tab_content').removeClass('active'); 
          jQuery(this).find('.front_fs_tabs_content .fs_tab_content:first').addClass('active');      
      });

    	});
    </script>
    <?php
  }

  if(!empty($_GET['post']) && $post_type == 'fs_tabs'){

?>
<script type="text/javascript">
      		jQuery('.page-title-action').text('<?php echo __("Manage Tab Sets", 'fs_tabs' ); ?>');
      		jQuery('.page-title-action').attr('href','<?php echo add_query_arg( array('post_type' => $post->post_type), admin_url('edit.php') ) ?>');
    </script>
<?php


  }
}

//main wp footer js script
add_action('wp_head', 'fstm_custom_bulk_wp_footer');
function fstm_custom_bulk_wp_footer() {

 ?>
  <script type="text/javascript">
  jQuery(document).ready(function() {
    if(jQuery('body').width() < 450) { 
      jQuery('body #fs_tab').addClass('fs_mobi');
    } else {
      jQuery('body #fs_tab').removeClass('fs_mobi');
    }
      });

     jQuery(window).resize(function(){
     if(jQuery('body').width() < 450) { 
      jQuery('body #fs_tab').addClass('fs_mobi');
     } else {
      jQuery('body #fs_tab').removeClass('fs_mobi');
    }
      });  
  </script>
  <?php 
}
?>

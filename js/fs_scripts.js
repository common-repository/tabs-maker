//function to get $_GET key in location.href
function $_GET(key) {
    var p = window.location.search;
    p = p.match(new RegExp(key + '=([^&=]+)'));
    return p ? p[1] : false;
}

//function make trim for white space before and after the string
function trim( str, charlist ) {    
	    charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
	    var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
	    return str.replace(re, '');
	}

(function ($) {
    $(function () {
    	if($('#fs-tabs-media-table li').length > '0' && !$_GET('tab')){
    		$('#fs_tabs_results_fields').show();
    	}

        $('body').on('click','.fs_tabs_nav li:not(.js_temp_content)',function(){
        var parent = $(this).parent().parent();
				
				if(!parent.hasClass('fs_mobi')){
            parent.find('.fs_tabs_nav:first').children().removeClass('active');
            $(this).addClass('active');
            parent.find('.front_fs_tabs_content:first').children().removeClass('active');
            parent.find('.front_fs_tabs_content:first').children().eq($(this).index()).addClass('active');

        } else {

            if($(this).index() != parent.find('.js_temp_content').attr('data-index')){
            parent.find('.js_temp_content').remove();
            parent.find('.fs_tabs_nav:first').children().removeClass('active');
            $(this).addClass('active');
            var js_content = '';
            parent.find('.front_fs_tabs_content .fs_tab_content').removeClass('active');
            parent.find('.front_fs_tabs_content .fs_tab_content').eq($(this).index()).addClass('active');

            js_content = parent.find('.fs_tab_content.active').html();
            
            if(!js_content) js_content = '';
            $(this).after('<li class="js_temp_content" data-index="'+$(this).index()+'">'+js_content+'</li>');
            parent.find('.js_temp_content').slideToggle(300);

          } else {
            parent.find('.js_temp_content').slideToggle(300);
        }
            
            
        }

         return false;
        });

        $('.fs_section h2.nav-tab-wrapper').delegate('a:not(.nav-tab-active)', 'click', function () {

        	if($(this).attr('id') == 'fs_main_tab' && $('#fs-tabs-media-table li').length > '0') $('#fs_tabs_results_fields').show();
        	else {$('#fs_tabs_results_fields').hide();}
            $(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active')
                .parents('div.fs_section').find('div.box').eq($(this).index())
                .show().siblings('div.box').hide();


        });

        //show main form for adding new tab
        $('#fs_add').click(function(){
        	$('#fs_tab_description-tmce').click();
        	$('.fs_add_section').show();
        });

        //hide main form for adding new tab
        $('#fs_add_cancel').click(function(){
        	$('#fs_add_tab').show();
        	$('#fs_edit_tab').hide();
        	$('.fs_add_section').hide();
        	$('#fs_tab_title').val('');
        	$('#fs_tab_ico').val('');
        	tinyMCE.editors['fs_tab_description'].dom.doc.body.innerHTML = '';
        	$('.fs_section .spinner').removeClass('is-active');
        });


         //add new tab
        $('#fs_add_tab').click(function(){

        	$('.fs_error').remove();
        	if($('#fs_tab_title').val() == ''){
        		$(window).scrollTop(0);
        		$('#fs_tab_title').after('<p class="fs_error">'+translation.fs_error_title_tab+'</p>');
        		return;
        	} 	

        	$('.fs_section .spinner').addClass('is-active');
        	if($('#fs-tabs-media-table li:last').length > 0) {
        			var sort = parseInt($('#fs-tabs-media-table li:last').attr('data-sort'))+1;
        			var num = parseInt($('#fs-tabs-media-table li:last').attr('data-sort'))+2;
        	} else {
        			var sort = '0';
        			var num = '1';
        	}

        	//save tab in db

        	var data = {
            action: "fstm_add_new_tab",
            post_id: $('input[name=post_ID]').val(),
            tab_title: $('#fs_tab_title').val(),
            tab_ico: $('#fs_tab_ico').val(),
            tab_content: tinyMCE.editors['fs_tab_description'].dom.doc.body.innerHTML,
            tab_sort: sort,
            tab_id: num,
        };

        	$.post(ajaxurl, data, function(response){
        			//console.log(response);
        			if(response.meta_id){
        	var new_tab = '<li class="fs-tabs-media-table-li" data-sort="'+sort+'"><div class="fs-tabs-media-table-id">'+num+'</div><div class="fs-tabs-media-table-tabico"><i class="fa fa-'+trim($('#fs_tab_ico').val())+' fa-2x"></i></div><div class="fs-tabs-media-table-tabtitle">'+trim($('#fs_tab_title').val())+'</div><div class="fs-tabs-media-table-buttons"><a class="fs-tabs-media-table-button fs-tabs-media-table-edit" data-id="'+response.meta_id+'">Edit</a>&nbsp;|&nbsp;&nbsp;<a class="fs-tabs-media-table-button fs-tabs-media-table-delete" data-id="'+response.meta_id+'">Delete</a>&nbsp;|&nbsp;&nbsp;<a class="fs-tabs-media-table-button fs-tabs-media-table-moveup">Move Up</a>&nbsp;|&nbsp;&nbsp;<a class="fs-tabs-media-table-button fs-tabs-media-table-movedown">Move Down</a></div><div class="clear"></div></li>';
        	
        	if($('#fs-tabs-media-table li:last').length > 0) $('#fs-tabs-media-table li:last').after(new_tab);
        	else {$('#fs-tabs-media-table').append(new_tab);$('#fs_tabs_results_fields').show();}
        	$('.fs_section .spinner').removeClass('is-active');

        	$('#fs_add_tab').show();
        	$('#fs_edit_tab').hide();
        	$('.fs_add_section').hide();
        	$('#fs_tab_title').val('');
        	$('#fs_tab_ico').val('');
        	tinyMCE.editors['fs_tab_description'].dom.doc.body.innerHTML = '';
        			}
            },'json');
        });

        //edit existing tab
        $('#fs_edit_tab').click(function(){
        	$('#fs_tab_description-tmce').click();
        	$('.fs_error').remove();
        	if($('#fs_tab_title').val() == ''){
        		$(window).scrollTop(0);
        		$('#fs_tab_title').after('<p class="fs_error">'+translation.fs_error_title_tab+'</p>');
        		return;
        	} 	


        	//edit tab in db
        		$('li[data-sort='+$(this).attr('data-sort')+']').find('.fs-tabs-media-table-tabtitle').text(trim($('#fs_tab_title').val()));
        		$('li[data-sort='+$(this).attr('data-sort')+']').find('.fs-tabs-media-table-tabico i').attr('class','fa fa-'+trim($('#fs_tab_ico').val())+' fa-2x');
        		$('#fs_add_tab').show();
        	$('#fs_edit_tab').hide();
        	$('.fs_add_section').hide();
        	var data = {
            action: "fstm_edit_tab",
            post_id: $('input[name=post_ID]').val(),
            tab_title: $('#fs_tab_title').val(),
            tab_ico: $('#fs_tab_ico').val(),
            tab_content: tinyMCE.editors['fs_tab_description'].dom.doc.body.innerHTML,
            tab_sort: $(this).attr('data-sort'),
            meta_id: $(this).attr('data-id'),
        };

        	$.post(ajaxurl, data, function(response){
				if(response){
       		$('#fs_tab_title').val('');
        	$('#fs_tab_ico').val('');
        	tinyMCE.editors['fs_tab_description'].dom.doc.body.innerHTML = '';
        	//console.log(response);
        }
        			
            },'json');
        });

        $('.fs-tab-skin input').click(function(){
						$('.fs-tab-skin').find('img').removeClass('selected');   
						$('.fs-tab-skin').find('input').prop('checked',false);    
						$(this).prop('checked',true); 
						$(this).parent().find('img').addClass('selected');  	
        });


        //movedown action then click on .fs-tabs-media-table-movedow in tabs list
        $(document).on("click", ".fs-tabs-media-table-movedown", function() {

            var $tr = $(this).closest(".fs-tabs-media-table-li");
            var index = $tr.index();
            
            var len = $('#fs-tabs-media-table li').length;
            if(len == 1) return;

            $('#fs_add_cancel').trigger('click');

            if (index == len - 1) {
                var $first = $tr.parent().find("li:first");
                $tr.remove();
                $first.before($tr)
            } else {
                var $next = $tr.next();
                $tr.remove();
                $next.after($tr)
            }
            $("#fs-tabs-media-table").find("li").each(function(index) {
                $(this).find(".fs-tabs-media-table-id").text(index + 1);
                $(this).attr("data-sort", index);
            });

            $(document).trigger('changesort');
        });

        //moveup action then click on .fs-tabs-media-table-moveup in tabs list
        $(document).on("click", ".fs-tabs-media-table-moveup", function() {
        		
            var $tr = $(this).closest(".fs-tabs-media-table-li");
            var index = $tr.index();
            //console.log(index);
            var len = $('#fs-tabs-media-table li').length;

            if(len == 1) return;

            $('#fs_add_cancel').trigger('click');

            if (index == '0' ) {
                var $last = $tr.parent().find("li:last");
                $tr.remove();
                $last.after($tr)
            } else {
                var $prev = $tr.prev();
                $tr.remove();
                $prev.before($tr)
            }
            $("#fs-tabs-media-table").find("li").each(function(index) {
                $(this).find(".fs-tabs-media-table-id").text(index + 1);
                $(this).attr("data-sort", index);
            });

            $(document).trigger('changesort');
        });

        //delete action then click on .fs-tabs-media-table-delete in tabs list
        $(document).on("click", ".fs-tabs-media-table-delete", function() {

            if (!confirm(translation.fs_tab_delete_text)) return false;
        		
        		$('#fs_add_cancel').trigger('click');

            $(this).parent().parent().remove();
            $("#fs-tabs-media-table").find("li").each(function(index) {
                $(this).find(".fs-tabs-media-table-id").text(index + 1);
                $(this).attr("data-sort", index);
            });

            if($("#fs-tabs-media-table li").length == '0') {
        			$('#fs_tabs_results_fields').hide();
        		}

           var id = $(this).attr('data-id');
           var data = {
            action: "fstm_delete_tab",
            meta_id: id,
        };

        	$.post(ajaxurl, data, function(response){
        			//console.log(response);
        			if(response.delete == 'ok'){
        				$(document).trigger('changesort');
        			}
            },'json');
        });

        //edit action then click on .fs-tabs-media-table-edit in tabs list
        $(document).on("click", ".fs-tabs-media-table-edit", function() {
        	$('#fs_tab_description-tmce').click();
        	var sort = $(this).parent().parent().attr('data-sort');
        	var meta_id = $(this).attr('data-id');
           var data = {
            action: "fstm_get_tab_to_edit",
            post_id: $('input[name=post_ID]').val(),
            meta_id: meta_id,
        };

        	$.post(ajaxurl, data, function(response){
        			//console.log(response);
        			if(response.tab_title){
        			$('#fs_tab_title').val(response.tab_title);
        			$('#fs_tab_ico').val(response.tab_ico);
        			tinyMCE.editors['fs_tab_description'].dom.doc.body.innerHTML = response.tab_content;
        			$('#fs_add_tab').hide();
        			$('#fs_edit_tab').attr({'data-sort':sort,'data-id':meta_id}).show();
        			$('.fs_add_section').show();
        		}
            },'json');
        });

        //load preview like AJAX
        $('#fs_preview_tab').on("click", function() {
        $('#fs_load_tabs').empty();
        $('#fs_load_tabs').append('<center>'+translation.fs_loading_text+'</center>');
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
                    var parent = jQuery('#fs_load_tabs');
                } else {
                var not_yet = '<center>'+translation.fs_no_tab_yet_text+'</center>';
                $('#fs_load_tabs').append(not_yet);
                $('#fs_tabs_prev_shortcode').val('');
                }
            },'json');
        });


        //sort tabs in list of tabs
        $(document).on("changesort", function() {
        		ids = [];
        		sorts = [];

        		$("#fs-tabs-media-table").find("li").each(function(index) {
               ids.push($(this).find('.fs-tabs-media-table-edit').attr('data-id'));
               sorts.push($(this).attr('data-sort'));
            });

            if(ids.length == '0') {
        			$('#fs_tabs_results_fields').hide();
        			return;
        		}

        		var data = {
            action: "fstm_order_all_tabs",
            post_id: $('input[name=post_ID]').val(),
            tabs_ids: ids,
            sorts: sorts,
        };
        	$.post(ajaxurl, data, function(response){
        			//console.log(response);
               },'json');
     	       });


    })
})(jQuery);

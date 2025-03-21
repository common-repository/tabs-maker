           var checkFlaShopMaps = function (el) {
            el.find('div[data-map-variable]').each(function(d) {

                var var_name = jQuery(this).data('map-variable'),
                    map = window[var_name];

                if (map) {
                    map.reloadMap();
                }
            })
        };

    //frontend necessary scripts for tabs work
    jQuery(document).ready(function ($) {
        $('body').on('click','#fs_tab .fs_tabs_nav_dop li',function(){
                var parent_dop = $(this).parent().parent();
                $('#fs_tab .fs_tabs_nav:first li').css('display','block');
                $('#fs_tab .fs_tabs_nav:first li').eq($(this).attr('data-index')).click();
                return false;

        });
        $('body').on('click','#fs_tab .fs_tabs_nav li:not(.js_temp_content)',function(){
        var parent = $(this).parent().parent();

        if(!parent.hasClass('fs_mobi')){
            parent.find('.fs_tabs_nav:first').children().removeClass('active');
            $(this).addClass('active');
            parent.find('.front_fs_tabs_content:first').children().removeClass('active');
            parent.find('.front_fs_tabs_content:first').children().eq($(this).index()).addClass('active');
            console.log('do_reload');
            checkFlaShopMaps(parent.find('.front_fs_tabs_content:first .fs_tab_content.active'));
       
        } else {
            if($(this).index() != parent.find('.front_fs_tabs_content .fs_tab_content.active').attr('data-index')){
                parent.find('.front_fs_tabs_content').hide();
                parent.find('.fs_tabs_nav_dop').remove();
                console.log('ALL');
                parent.find('.fs_tabs_nav:first').children().removeClass('active');
                $(this).addClass('active');
                var dop_menu = '';
                if(parent.find('.fs_tabs_nav:first li:last').index() != $(this).index()){
                parent.find('.fs_tabs_nav:first li').each(function(){
                    console.log($(this).index());
                    if(!$(this).hasClass('active') && $(this).index() > parent.find('.fs_tabs_nav:first li.active').index()){
                        dop_menu += '<li data-index="'+$(this).index()+'">'+$(this).html()+'</li>';
                        $(this).hide();
                    }
                });
                if(dop_menu) parent.find('.front_fs_tabs_content').after('<ul class="fs_tabs_nav_dop">'+dop_menu+'</ul>');
                }
                parent.find('.front_fs_tabs_content .fs_tab_content').removeClass('active');
                parent.find('.front_fs_tabs_content .fs_tab_content').eq($(this).index()).addClass('active');
                parent.find('.front_fs_tabs_content .fs_tab_content.active').attr('data-index',$(this).index());

                parent.find('.front_fs_tabs_content').slideToggle(300,function(){
                    
                });
                checkFlaShopMaps(parent.find('.front_fs_tabs_content .fs_tab_content.active'));
                
                
            } else {
                console.log('only_toggle');
                parent.find('.front_fs_tabs_content').slideToggle(300);
            }
        }

         return false;
        });

        checkFlaShopMaps($('#fs_tab .fs_tab_content.active'));



       
    });


     jQuery(window).resize(function(){
            jQuery('body #fs_tab').each(function(){
                jQuery(this).find('.front_fs_tabs_content').removeAttr('style');
                jQuery(this).find('.front_fs_tabs_content .fs_tab_content.active').removeAttr('data-index');
                jQuery(this).find('.fs_tabs_nav:first li').css('display','block');
                jQuery(this).find('.fs_tabs_nav_dop').remove();
            });
            setTimeout(function () { checkFlaShopMaps(jQuery('#fs_tab .fs_tab_content.active')) }, 100);
            
        });

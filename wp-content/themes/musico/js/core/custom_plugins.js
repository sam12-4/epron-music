jQuery(document).ready(function(){ 
	"use strict";

	jQuery.fn.center = function ()
	{
	    this.css("left", (jQuery(window).width() / 2) - (this.outerWidth() / 2));
	    return this;
	}
	
	//Desktop menu
	jQuery('#menu_wrapper div .nav li a, .mobile_main_nav li a').on( 'click', function(event){
		var documentScroll = jQuery(document).scrollTop();
		var linkURL = jQuery(this).attr('href');
		var sectionID = jQuery(this).attr('href').substr(1);
	
		if(linkURL.slice(0,1)=='#' && sectionID != '')
		{
			event.preventDefault();
			var topBarHeight = jQuery('.top_bar').height();
			
			if(!jQuery(this).parent('li').hasClass('menu-item-has-children'))
			{
				jQuery('#close_mobile_menu').trigger('click');
			}
			
			if(sectionID=='top')
			{
				jQuery('body,html').animate({scrollTop:0},1200);
			}
			else
			{
				if(documentScroll != 0)
				{
					var scrollToPos = parseInt(jQuery('#'+sectionID).offset().top-topBarHeight);
				}
				else
				{
					var scrollToPos = parseInt(jQuery('#'+sectionID).offset().top-topBarHeight+32);
				}
			
				jQuery('body,html').animate({
				    scrollTop: scrollToPos
				}, 1200);
			}
			
			jQuery('#menu_wrapper div .nav li').removeClass('current-menu-item');
			jQuery(this).parent('li').addClass('current-menu-item');
			jQuery('body').removeClass('js_nav');
		}
		else
		{
			return true;
		}
	});
	
	jQuery('#menu_wrapper div .nav li a').each(function () {
		var sectionElement = jQuery(this).attr('href');
		
		if(typeof sectionElement != 'undefined' && sectionElement.charAt(0) == '#')
		{
			var topBarHeight = jQuery('.top_bar').height();
		
			jQuery(sectionElement).waypoint(function(direction) {
				jQuery('#menu_wrapper div .nav li a').each(function(){
					if(jQuery(this).attr('href')==sectionElement)
					{
						jQuery('#menu_wrapper div .nav li').removeClass('current-menu-item');
						jQuery(this).parent('li').addClass('current-menu-item');
					}
				})
			}, { offset: topBarHeight });
		}
	});
	
	jQuery.fn.setNav = function(){
		var calScreenWidth = jQuery(window).width();
		var menuLayout = jQuery('#pp_menu_layout').val();
		
		if(calScreenWidth >= 960)
		{
			
			jQuery('#menu_wrapper .nav li.menu-item').hover(function()
			{
				jQuery(this).children('ul:first').addClass('visible');
				jQuery(this).children('ul:first').addClass('hover');
			},
			function()
			{	
				jQuery(this).children('ul:first').removeClass('visible');
				jQuery(this).children('ul:first').removeClass('hover');
			});
			
			jQuery('#menu_wrapper .nav li.menu-item').children('ul:first.hover').hover(function()
			{
				jQuery(this).stop().addClass('visible');
			},
			function()
			{	
				jQuery(this).stop().removeClass('visible');
			});
		}
		
		jQuery('body').on('click', '.mobile_main_nav > li a', function(event) {
		    var jQuerysublist = jQuery(this).parent('li').find('ul.sub-menu:first');
		    var menuContainerClass = jQuery(this).parent('li').parent('#mobile_main_menu.mobile_main_nav').parent('div');
		    
		    if(jQuerysublist.length>0)
		    {
			    event.preventDefault();
		    }
		    
		    var menuLevel = 'top_level';
		    var parentMenu = '';
		    var menuClickedId = jQuery(this).attr('id');
		    
		    if(jQuery(this).parent('li').parent('ul').attr('id')=='mobile_main_menu')
		    {
			    menuLevel = 'parent_level';
		    }
		    else
		    {
			    parentMenu = jQuery(this).parent('li').attr('id');
		    }
	
		    if(jQuerysublist.length>0)
		    {
			    jQuery('#mobile_main_menu.mobile_main_nav').addClass('mainnav_out');
			    jQuery('.mobile_menu_wrapper div #sub_menu').removeClass('subnav_in');
			    jQuery('.mobile_menu_wrapper div #sub_menu').addClass('mainnav_out');
			    
			    if(jQuery('#pp_menu_layout').val() == 'hammenufull')
			    {
				    jQuery('.mobile_menu_wrapper .logo_container').fadeOut('slow');
				    jQuery('.mobile_menu_wrapper .social_wrapper').fadeOut('slow');
			    }
			    
			    setTimeout(function() {
			    	jQuery('#mobile_main_menu.mobile_main_nav').css('display', 'none');
			    	jQuery('.mobile_menu_wrapper div #sub_menu').remove();
			    
			        var subMenuHTML = '<li><a href="#" id="menu_back" class="'+menuLevel+'" data-parent="'+parentMenu+'">'+jQuery('#pp_back').val()+'</a></li>';
			        subMenuHTML += jQuerysublist.html();
			        
			    	menuContainerClass.append('<ul id="sub_menu" class="nav '+menuLevel+'"></ul>');
			    	menuContainerClass.find('#sub_menu').html(subMenuHTML);
			    	menuContainerClass.find('#sub_menu').addClass('subnav_in');
			    }, 200);
		    }
		});
		
		jQuery('body').on('click', '#menu_back.parent_level', function() {
			jQuery('.mobile_menu_wrapper div #sub_menu').removeClass('subnav_in');
			jQuery('.mobile_menu_wrapper div #sub_menu').addClass('subnav_out');
			jQuery('#mobile_main_menu.mobile_main_nav').removeClass('mainnav_out');
			
			if(jQuery('#pp_menu_layout').val() == 'hammenufull')
			{
			    jQuery('.mobile_menu_wrapper .logo_container').fadeIn('slow');
			    jQuery('.mobile_menu_wrapper .social_wrapper').fadeIn('slow');
			}
			
			setTimeout(function() {
				jQuery('.mobile_menu_wrapper div #sub_menu').remove();
				jQuery('#mobile_main_menu.mobile_main_nav').css('display', 'block');
				jQuery('#mobile_main_menu.mobile_main_nav').addClass('mainnav_in');
			}, 200);
		});
		
		jQuery('body').on('click', '#menu_back.top_level', function() {
			event.preventDefault();
			jQuery('.mobile_menu_wrapper div #sub_menu').addClass('subnav_out');
			var parentMenuId = jQuery(this).data('parent');
			
			setTimeout(function() {
				jQuery('.mobile_menu_wrapper div #sub_menu').remove();
				var menuLevel = 'top_level';
				var parentMenu = '';
	
				if(jQuery('#mobile_main_menu.mobile_main_nav li#'+parentMenuId).parent('ul.sub-menu:first').parent('li').parent('ul#main_menu').length == 1)
				{
					menuLevel = 'parent_level';
				}
				else
				{
					parentMenu = jQuery('#mobile_main_menu.mobile_main_nav li#'+parentMenuId).parent('ul.sub-menu:first').parent('li').attr('id');
				}
				
				var subMenuHTML = '<li><a href="#" id="menu_back" class="'+menuLevel+'" data-parent="'+parentMenu+'">'+jQuery('#pp_back').val()+'</a></li>';
				subMenuHTML+= jQuery('#mobile_main_menu.mobile_main_nav li#'+parentMenuId).parent('ul.sub-menu:first').html();
				jQuery('.mobile_menu_wrapper div').append('<ul id="sub_menu" class="nav '+menuLevel+'"></ul>');
				    
				jQuery('.mobile_menu_wrapper div #sub_menu').html(subMenuHTML);
				jQuery('.mobile_menu_wrapper div #sub_menu').addClass('mainnav_in');
			}, 200);
		});
	}
});

function adjustIframes()
{
  jQuery('iframe').each(function(){
  
    var
    $this       = jQuery(this),
    proportion  = $this.data( 'proportion' ),
    w           = $this.attr('width'),
    actual_w    = $this.width();
    
    if ( ! proportion )
    {
        proportion = $this.attr('height') / w;
        $this.data( 'proportion', proportion );
    }
  
    if ( actual_w != w )
    {
        $this.css( 'height', Math.round( actual_w * proportion ) + 'px !important' );
    }
  });
}

function is_touch_device() {
  return 'ontouchstart' in window // works on most browsers 
      || 'onmsgesturechange' in window; // works on ie10
}

function triggerClick(element) {
    if(document.createEvent) {
        var evt = document.createEvent("MouseEvents");
        evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        element.dispatchEvent(evt);
    }
    else {
        element.click();
    }
}
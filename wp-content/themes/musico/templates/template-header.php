<?php
/**
*	Get Current page object
**/
if(!is_null($post))
{
	$page_obj = get_page($post->ID);
}

$current_page_id = '';

/**
*	Get current page id
**/

if(!is_null($post) && isset($page_obj->ID))
{
    $current_page_id = $page_obj->ID;
}

//Get Page Menu Transparent Option
$page_menu_transparent = get_post_meta($current_page_id, 'page_menu_transparent', true);

//Disable menu transparent for default blog homepage
if (is_home()) {
	$page_menu_transparent = 0;
}

//Get Boxed Layout Option
$page_boxed_layout = get_post_meta($current_page_id, 'page_boxed_layout', true);

//Get page header display setting
$page_title = get_the_title();
$page_show_title = get_post_meta($current_page_id, 'page_show_title', true);

if(is_tag())
{
	$page_show_title = 0;
	$page_title = single_cat_title( '', false );
	$page_title = ucfirst($page_title);
	$term = 'tag';
} 
elseif(is_category())
{
    $page_show_title = 0;
	$page_title = single_cat_title( '', false );
	$term = 'category';
}
elseif(is_archive())
{
	$page_show_title = 0;

	if ( is_day() ) : 
		$page_title = get_the_date(); 
    elseif ( is_month() ) : 
    	$page_title = get_the_date('F Y'); 
    elseif ( is_year() ) : 
    	$page_title = get_the_date('Y'); 
    elseif ( !empty($term) ) : 
    	$ob_term = get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
    	$page_taxonomy = get_taxonomy($ob_term->taxonomy);
    	$page_title = $ob_term->name;
    else :
    	$page_title = esc_html__('Blog Archives', 'musico'); 
    endif;
    
    $term = 'archive';
    
}
else if(is_search())
{
	$page_show_title = 0;
	$page_title = esc_html__('Search', 'musico' );
	$term = 'search';
}
else if(is_home())
{
	$page_show_title = 0;
	$page_title = esc_html__('Blog', 'musico' );
	$term = 'home';
}

$musico_page_content_class = musico_get_page_content_class();

$musico_hide_title = musico_get_hide_title();
if($musico_hide_title == 1)
{
	$page_show_title = 1;
}

$musico_screen_class = musico_get_screen_class();
if($musico_screen_class == 'split' OR $musico_screen_class == 'single_client')
{
	$page_show_title = 0;
}
if($musico_screen_class == 'single_client')
{
	$page_show_title = 1;
}

if(isset($_GET['elementor_library']) && !empty($_GET['elementor_library']))
{
	$page_show_title = 1;
}

//Check Elementor page hide title option
$elementor_page_settings = get_post_meta($current_page_id, '_elementor_page_settings');
if(isset($elementor_page_settings[0]['hide_title']))
{
	$page_show_title = 1;
}

if(empty($page_show_title))
{
	//Get current page tagline
	$page_tagline = get_post_meta($current_page_id, 'page_tagline', true);
	
	if(is_category())
	{
		$page_tagline = category_description();
	}
	
	if(is_tag())
	{
		$page_tagline = category_description();
	}
	
	if(is_archive() && !is_category() && !is_tag() && empty($term))
	{
		$page_tagline = esc_html__('Archive posts in ', 'musico' );
		
		if ( is_day() ) : 
			$page_tagline.= get_the_date(); 
	    elseif ( is_month() ) : 
	    	$page_tagline.= get_the_date('F Y'); 
	    elseif ( is_year() ) : 
	    	$page_tagline.= get_the_date('Y');
	    endif;
	}
	
	//If on gallery post type page
	if(is_single() && $post->post_type == 'galleries')
	{
		$page_tagline = get_the_excerpt();
	}
	
	if(is_search())
	{
		$page_tagline = esc_html__('Search Results for ', 'musico' ).get_search_query();
	}

	if(!empty($term) && !is_tag())
	{
		$ob_term = get_term_by('slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		if(isset($ob_term->description))
		{
			$page_tagline = $ob_term->description;
		}
	}

	$pp_page_bg = '';
	
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full') && empty($term))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        
        if(isset($image_thumb[0]) && !empty($image_thumb[0]))
        {
        	$pp_page_bg = $image_thumb[0];
        }
        
        //Check if add parallax effect
		$tg_page_header_bg_parallax = get_theme_mod('tg_page_header_bg_parallax');
		if(!empty($tg_page_header_bg_parallax))
		{
			wp_enqueue_script("jarallax", get_template_directory_uri()."/js/jarallax.js", false, MUSICO_THEMEVERSION, true);
			wp_enqueue_script("jarallax-video", get_template_directory_uri()."/js/jarallax-video.js", false, MUSICO_THEMEVERSION, true);
			wp_enqueue_script("jarallax-element", get_template_directory_uri()."/js/jarallax-element.js", false, MUSICO_THEMEVERSION, true);
			
			$custom_jarallax_script = "
			jQuery(function( $ ) {
				var parallaxSpeed = 0.2;
			    if(jQuery(window).width() > 1200)
			    {
				    parallaxSpeed = 0.5;
			    }
				 
				var ua = window.navigator.userAgent;
			    var msie = ua.indexOf('MSIE ');
			    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./) || jQuery(this).width() < 768)
			    {
				    jQuery('.parallax').each(function(){
				    	var dataImgURL = jQuery(this).data('image');
				    	if(jQuery.type(dataImgURL) != 'undefined')
				    	{
				    		jQuery(this).css('background-image', 'url('+dataImgURL+')');
				    		jQuery(this).css('background-size', 'cover');
				    		jQuery(this).css('background-position', 'center center');
				    	}
				    });
			    }
			    else
			    {
				    var parallaxSpeed = 0.5;
				    if(jQuery(window).width() > 1200)
				    {
					    parallaxSpeed = 0.7;
				    }
				    
			    	jQuery('.parallax').each(function(){
			    		var parallaxObj = jQuery(this);
			    	
					 	jQuery(this).jarallax({
					 		zIndex          : 0,
					 		speed			: parallaxSpeed,
					 		onCoverImage: function() {
						        parallaxObj.css('z-index', 0);
						    }
					 	});
					 });
			    }
			});
			";
			
			wp_add_inline_script('jarallax-element', $custom_jarallax_script);
		}
    }
	
	$musico_topbar = musico_get_topbar();
	$page_header_type = '';
	
	if(is_page())
	{
		if(MUSICO_THEMEDEMO)
		{
			$page_title_split_arr = explode(' ', $page_title);
			
			if(isset($page_title_split_arr[0]) && $page_title_split_arr[0] == 'Blog')
			{
				$page_title = 'Blog';
			}
		}
		
		//Get header featured content
		$page_header_type = get_post_meta(get_the_ID(), 'page_header_type', true);
		
		$video_url = '';
					
		if($page_header_type == 'Youtube Video' OR $page_header_type == 'Vimeo Video')
		{
			//Add jarallax video script
			wp_enqueue_script("jarallax-video", get_template_directory_uri()."/js/jarallax-video.js", false, MUSICO_THEMEVERSION, true);
			
			if($page_header_type == 'Youtube Video')
			{
				$page_header_youtube = get_post_meta(get_the_ID(), 'page_header_youtube', true);
				$video_url = 'https://www.youtube.com/watch?v='.$page_header_youtube;
			}
			else
			{
				$page_header_vimeo = get_post_meta(get_the_ID(), 'page_header_vimeo', true);
				$video_url = 'https://vimeo.com/'.$page_header_vimeo;
			}
		}
	}
?>
<div id="page_caption" class="<?php if(!empty($pp_page_bg) OR $page_header_type == 'Youtube Video' OR $page_header_type == 'Vimeo Video') { ?>hasbg <?php if(!empty($tg_page_header_bg_parallax)) { ?>parallax<?php } ?> <?php } ?> <?php if(!empty($musico_topbar)) { ?>withtopbar<?php } ?> <?php if(!empty($musico_screen_class)) { echo esc_attr($musico_screen_class); } ?> <?php if(!empty($musico_page_content_class)) { echo esc_attr($musico_page_content_class); } ?>" <?php if(!empty($pp_page_bg)) { ?>style="background-image:url(<?php echo esc_url($pp_page_bg); ?>);"<?php } ?> <?php if($page_header_type == 'Youtube Video' OR $page_header_type == 'Vimeo Video') { ?>data-jarallax-video="<?php echo esc_url($video_url); ?>"<?php } ?>>

	<?php
		if(empty($page_show_title))
		{
	?>
		<?php 
			if(!empty($pp_page_bg) OR $page_header_type == 'Youtube Video' OR $page_header_type == 'Vimeo Video') 
			{
		?>
			<div id="page_caption_overlay"></div>
		<?php
			}
		?>
	<div class="page_title_wrapper">
		<div class="standard_wrapper">
			<div class="page_title_inner">
				<div class="page_title_content">
					<h1 <?php if(!empty($pp_page_bg) && !empty($musico_topbar)) { ?>class ="withtopbar"<?php } ?>><?php echo esc_html($page_title); ?></h1>
					<?php
				    	if(!empty($page_tagline))
				    	{
				    ?>
				    	<div class="page_tagline">
				    		<?php echo nl2br($page_tagline); ?>
				    	</div>
				    <?php
				    	}
				    ?>
				</div>
			</div>
		</div>
	</div>
	<?php
		}
	?>

</div>
<?php
}
?>

<!-- Begin content -->
<div id="page_content_wrapper" class="<?php if(!empty($page_boxed_layout)) { ?>blog_wrapper <?php } ?><?php if(!empty($pp_page_bg)) { ?>hasbg <?php } ?><?php if(!empty($pp_page_bg) && !empty($musico_topbar)) { ?>withtopbar <?php } ?><?php if(!empty($musico_page_content_class)) { echo esc_attr($musico_page_content_class); } ?>">
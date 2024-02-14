<?php
/**
 * The main template file for display error page.
 *
 * @package WordPress
*/


get_header(); 
?>

<!-- Begin content -->
<div id="page_caption">
	<div class="page_title_wrapper">
		<div class="standard_wrapper">
			<div class="page_title_inner">
			    <h1><?php esc_html_e('404 Not Found!', 'musico' ); ?></h1>
			</div>
		</div>
	</div>
</div>

<div id="page_content_wrapper">

    <div class="inner">
    
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    	
	    	<div class="search_form_wrapper">
		    	<?php esc_html_e( "We're sorry, the page you have looked for does not exist in our content! Perhaps you would like to go to our homepage or try searching below.", 'musico' ); ?>
		    	<br/><br/>
		    	
	    		<form class="searchform" method="get" action="<?php echo esc_url(home_url('/')); ?>">
		    		<p class="input_wrapper">
			    		<input type="text" class="input_effect field searchform-s" name="s" value="<?php the_search_query(); ?>" placeholder="<?php esc_attr_e('Type to search...', 'musico' ); ?>">
			    	<span class="focus-border"></span>
		    		</p>
			    	<br/>
			    	<input type="submit" value="<?php esc_attr_e('Search', 'musico' ); ?>"/>
			    </form>
    		</div>
	    	
	    	<br/>
	    	
    		</div>
    	</div>
    	
</div>
<br class="clear"/>
<?php get_footer(); ?>
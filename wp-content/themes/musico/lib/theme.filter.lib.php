<?php
if(MUSICO_THEMEDEMO) {
	add_action( 'wp_enqueue_scripts', 'dotlife_juice_cleanse', 200 );
	function dotlife_juice_cleanse() {
	
		wp_dequeue_style('wp-block-library');
	
		// This also removes some inline CSS variables for colors since 5.9 - global-styles-inline-css
		wp_dequeue_style('global-styles');
	
		// WooCommerce - you can remove the following if you don't use Woocommerce
		wp_dequeue_style('wc-block-style');
		wp_dequeue_style('wc-blocks-vendors-style');
		wp_dequeue_style('wc-blocks-style'); 
	}
}

/**
 * This function runs when WordPress completes its upgrade process
 * It iterates through each plugin updated to see if ours is included
 * @param $upgrader_object Array
 * @param $options Array
 */
function musico_upgrade_completed( $upgrader_object, $hook_extra ) { 
	
	if ($hook_extra['type'] = 'theme' && !MUSICO_THEMEDEMO) {
		//Get verified purchase code data
		$is_verified_envato_purchase_code = musico_is_registered();
		
		//Check if registered purchase code valid
		if(!empty($is_verified_envato_purchase_code)) {
			$site_domain = musico_get_site_domain();
			
			if($site_domain != 'localhost') {
				$url = THEMEGOODS_API.'/check-purchase-domain';
				//var_dump($url);
				$data = array(
					'purchase_code' => $is_verified_envato_purchase_code, 
					'domain' => $site_domain,
				);
				$data = wp_json_encode( $data );
				$args = array( 
					'method'   	=> 'POST',
					'body'		=> $data,
				);
				//print '<pre>'; var_dump($args); print '</pre>';
				
				$response = wp_remote_post( $url, $args );
				$response_body = wp_remote_retrieve_body( $response );
				$response_obj = json_decode($response_body);
				
				$response_json = urlencode($response_body);
				
				//If no data then unregister theme
				if(!$response_obj->response_code OR empty($response_body)) {
					
				}
				else {
					if(!empty($response_body)) {
						$response_body_obj = json_decode($response_body);
						
						if(!$response_body_obj->response[0]->domain) {
							
						}
						else {
							/*print '<pre>'; var_dump($response_body_obj->response[0]->domain); print '</pre>';
							die;*/
							if(!empty($response_body_obj->response[0]->domain) && $response_body_obj->response[0]->domain != $site_domain) {
								musico_unregister_theme();
							}
						}
					}
				}
			}
		}
	}
}
add_action( 'upgrader_process_complete', 'musico_upgrade_completed', 10, 2 );

//Remove one click demo import plugin from admin menus
function musico_plugin_page_setup( $default_settings ) {
	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'Demo Import' , 'musico' );
	$default_settings['menu_title']  = esc_html__( 'Import Demo Content' , 'musico' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'tg-one-click-demo-import';

	return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'musico_plugin_page_setup' );

function musico_menu_page_removing() {
    remove_submenu_page( 'themes.php', 'tg-one-click-demo-import' );
}
add_action( 'admin_menu', 'musico_menu_page_removing', 99 );
	
$is_verified_envato_purchase_code = false;

//Get verified purchase code data
$is_verified_envato_purchase_code = musico_is_registered();

if($is_verified_envato_purchase_code)
{
	function musico_import_files() {
	  return array(
	    array(
	      'import_file_name'             => 'Demo 1',
	      'local_import_file'            => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo1/1.xml',
	      'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo1/1.wie',
	      'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'cache/demos/xml/demo1/1.dat',
	      'preview_url'                  => 'https://themes.themegoods.com/musico/demo/',
	    ),
	  );
	}
	add_filter( 'pt-ocdi/import_files', 'musico_import_files' );
	
	function musico_confirmation_dialog_options ( $options ) {
		return array_merge( $options, array(
			'width'       => 300,
			'dialogClass' => 'wp-dialog',
			'resizable'   => false,
			'height'      => 'auto',
			'modal'       => true,
		) );
	}
	add_filter( 'pt-ocdi/confirmation_dialog_options', 'musico_confirmation_dialog_options', 10, 1 );
	
	function musico_after_import( $selected_import ) {
		switch($selected_import['import_file_name'])
		{
			case 'Demo 1':
			default:
				// Assign menus to their locations.
				$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
				$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
			
				set_theme_mod( 'nav_menu_locations', array(
						'primary-menu' => $main_menu->term_id,
						'side-menu' => $main_menu->term_id,
						'footer-menu' => $footer_menu->term_id,
					)
				);
				
			break;
		}
		
		// Assign front page
		$front_page_id = get_page_by_title( 'Home 1' );
		
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		
		// Assign Woocommerce related page
		$shop_page_id = get_page_by_title( 'Shop' );
		update_option( 'woocommerce_shop_page_id', $shop_page_id->ID );
		
		$cart_page_id = get_page_by_title( 'Cart' );
		update_option( 'woocommerce_cart_page_id', $cart_page_id->ID );
		
		$checkout_page_id = get_page_by_title( 'Checkout' );
		update_option( 'woocommerce_checkout_page_id', $checkout_page_id->ID );
		
		$myaccount_page_id = get_page_by_title( 'My account' );
		update_option( 'woocommerce_myaccount_page_id', $myaccount_page_id->ID );
		
		//Setup theme custom font
		remove_theme_mod('tg_custom_fonts');
		
		$default_custom_fonts = array(
			0 => array(
				'font_name' => 	'Renner',
				'font_url' 	=>	get_template_directory_uri().'/fonts/renner-book-webfont.woff',
				'font_fallback'	=> 'sans-serif',
				'font_weight' => 400,
				'font_style' => 'normal',
			),
			1 => array(
				'font_name' => 	'Renner',
				'font_url' 	=>	get_template_directory_uri().'/fonts/renner-bold-webfont.woff',
				'font_fallback'	=> 'sans-serif',
				'font_weight' => 700,
				'font_style' => 'normal',
			),
			2 => array(
				'font_name' => 	'Renner',
				'font_url' 	=>	get_template_directory_uri().'/fonts/renner-black-webfont.woff',
				'font_fallback'	=> 'sans-serif',
				'font_weight' => 900,
				'font_style' => 'normal',
			)
		);
		set_theme_mod( 'tg_custom_fonts', $default_custom_fonts );
		
		//Set default footer content
		set_theme_mod( 'tg_footer_content', 'content' );
		
		// 'Hello World!' post
	    wp_delete_post( 4, true );
	
	    // 'Sample page' page
	    wp_delete_post( 5, true );
	    
	    //Set permalink
	    global $wp_rewrite;
		$wp_rewrite->set_permalink_structure('/%postname%/');
		
		//Update all Elementor URLs
		musico_elementor_replace_urls('https://themes.themegoods.com/musico/demo', home_url());

	}
	add_action( 'pt-ocdi/after_import', 'musico_after_import' );
	add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
}

//Disable Elementor getting started
add_action( 'admin_init', function() {
	if ( did_action( 'elementor/loaded' ) ) {
		remove_action( 'admin_init', [ \Elementor\Plugin::$instance->admin, 'maybe_redirect_to_getting_started' ] );
	}
}, 1 );

add_filter( 'the_password_form', 'musico_password_form' );
function musico_password_form() {
    $post = musico_get_wp_post();
    
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    
    $return_html = '<div class="protected-post-header"><h1>' . esc_html($post->post_title) . '</h1></div>';
    $return_html.= '<form class="protected-post-form" action="' .wp_login_url(). '?action=postpass" method="post">';
    $return_html.= esc_html__( "This content is password protected. To view it please enter your password below:", 'musico'  ).'<p><input name="post_password" id="' . $label . '" type="password" size="40" /></p>';
    
    $return_html.= '<p><input type="submit" name="Submit" class="button" value="' . esc_html__( "Authenticate", 'musico' ) . '" /></p>';
    $return_html.= '</form>';
    
    return $return_html;
}
	
if ( ! function_exists( 'musico_theme_kirki_update_url' ) ) {
    function musico_theme_kirki_update_url( $config ) {
        $config['url_path'] = get_template_directory_uri() . '/modules/kirki/';
        return $config;
    }
}
add_filter( 'kirki/config', 'musico_theme_kirki_update_url' );

add_action( 'customize_register', function( $wp_customize ) {
	/**
	 * The custom control class
	 */
	class Kirki_Controls_Title_Control extends Kirki_Control_Base {
		public $type = 'title';
		public function render_content() { 
			echo esc_html($this->label);
		}
	}
	// Register our custom control with Kirki
	add_filter( 'kirki/control_types', function( $controls ) {
		$controls['title'] = 'Kirki_Controls_Title_Control';
		return $controls;
	} );

} );

//add_action( 'admin_footer', 'musico_welcome_dashboard_widget' );
function musico_welcome_dashboard_widget() {
 // Bail if not viewing the main dashboard page
 if ( get_current_screen()->base !== 'dashboard' ) {
  return;
 }
 ?>

 <div id="musico-welcome-id" class="welcome-panel" style="display: none;">
  <div class="welcome-panel-content">
	  <div style="height:10px"></div>
   <h2>Welcome to <?php echo esc_html(MUSICO_THEMENAME); ?> Theme</h2>
   <p class="about-description">Welcome to <?php echo esc_html(MUSICO_THEMENAME); ?> theme. <?php echo esc_html(MUSICO_THEMENAME); ?> is now installed and ready to use! Read below for additional informations. We hope you enjoy using the theme!</p>
   
   <br style="clear:both;"/>
   
   <div class="welcome-panel-column-container">
    
    <div class="one_half">
		<div class="step_icon">
			<a href="<?php echo admin_url("themes.php?page=install-required-plugins"); ?>">
				<i class="fas fa-plug"></i>
				<div class="step_title">Install Plugins</div>
			</a>
		</div>
		<div class="step_info">
			<?php echo esc_html(MUSICO_THEMENAME); ?> has required and recommended plugins in order to build your website using layouts you saw on our demo site. We recommend you to install all plugins first.
		</div>
	</div>
	
	<div class="one_half last">
		<div class="step_icon">
			<a href="<?php echo admin_url("post-new.php?post_type=page"); ?>">
				<i class="fa fa-file-alt"></i>
				<div class="step_title">Create Page</div>
			</a>
		</div>
		<div class="step_info">
			<?php echo esc_html(MUSICO_THEMENAME); ?> support standard WordPress page option. You can also use Elementor page builder to create and organise page contents.
		</div>
	</div>
	
	<div class="one_half">
		<div class="step_icon">
			<a href="<?php echo admin_url("customize.php"); ?>">
				<i class="fas fa-sliders-h"></i>
				<div class="step_title">Customize Theme</div>
			</a>
		</div>
		<div class="step_info">
			Start customize theme's layouts, typography, elements colors using WordPress customize and see your changes in live preview instantly.
		</div>
	</div>
	
	<div class="one_half last">
		<div class="step_icon">
			<a href="<?php echo admin_url("themes.php?page=functions.php#pp_panel_import-demo"); ?>">
				<i class="fas fa-database"></i>
				<div class="step_title">Import Demo</div>
			</a>
		</div>
		<div class="step_info">
			We created various ready to use pages for you to use as starting point of your website. We recommend you to install all recommended plugins before importing ready site contents.
		</div>
	</div>
	
	<br style="clear:both;"/>
	
	<h1>Support</h1>
	<div style="height:20px"></div>
	<div class="one_half nomargin">
		<div class="step_icon">
			<a href="https://themegoods.ticksy.com/submit/" target="_blank">
				<i class="fas fa-life-ring"></i>
				<div class="step_title">Submit a Ticket</div>
			</a>
		</div>
		<div class="step_info">
			We offer excellent support through our ticket system. Please make sure you prepare your purchased code first to access our services.
		</div>
	</div>
	
	<div class="one_half last nomargin">
		<div class="step_icon">
			<a href="http://docs.themegoods.com/docs/musico" target="_blank">
				<i class="fas fa-book"></i>
				<div class="step_title">Theme Document</div>
			</a>
		</div>
		<div class="step_info">
			This is the place to go find all reference aspects of theme functionalities. Our online documentation is resource for you to start using theme.
		</div>
	</div>
	
	<br style="clear:both;"/>
	
	<div style="height:30px"></div>
    
   </div>
  </div>
 </div>
 <script>
  jQuery(document).ready(function($) {
   	jQuery('#welcome-panel').after($('#musico-welcome-id').show());
  });
 </script>

<?php }

//Make widget support shortcode
add_filter('widget_text', 'do_shortcode');

function musico_tag_cloud_filter($args = array()) {
   $args['smallest'] = 12;
   $args['largest'] = 12;
   $args['unit'] = 'px';
   return $args;
}

add_filter('widget_tag_cloud_args', 'musico_tag_cloud_filter', 90);

//Customise Widget Title Code
add_filter( 'dynamic_sidebar_params', 'musico_wrap_widget_titles', 1 );
function musico_wrap_widget_titles( array $params ) 
{
	$widget =& $params[0];
	$widget['before_title'] = '<h2 class="widgettitle"><span>';
	$widget['after_title'] = '</span></h2>';
	
	return $params;
}

//Control post excerpt length
function musico_custom_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'musico_custom_excerpt_length', 200 );


function musico_theme_queue_js(){
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { 
    // enqueue the javascript that performs in-link comment reply fanciness
    wp_enqueue_script( 'comment-reply' ); 
  }
}
add_action('get_header', 'musico_theme_queue_js');


function musico_add_meta_tags() {
    $post = musico_get_wp_post();
    
    echo '<meta charset="'.get_bloginfo( 'charset' ).'" />';
    
    //Check if responsive layout is enabled
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />';
	
	//meta for phone number link on mobile
	echo '<meta name="format-detection" content="telephone=no">';
}
add_action( 'wp_head', 'musico_add_meta_tags' , 2 );

add_filter('redirect_canonical','custom_disable_redirect_canonical');
function custom_disable_redirect_canonical($redirect_url) {if (is_paged() && is_singular()) $redirect_url = false; return $redirect_url; }

add_action('elementor/widgets/widgets_registered', 'musico_unregister_elementor_widgets');

function musico_unregister_elementor_widgets($obj){
	$obj->unregister_widget_type('sidebar');
}

function musico_body_class_names($classes) 
{
	$post = musico_get_wp_post();
	
	if(isset($post->ID))
	{
		//Check if boxed layout is enable
		$page_boxed_layout = get_post_meta($post->ID, 'page_boxed_layout', true);
		if(!empty($page_boxed_layout))
		{
			$classes[] = esc_attr('tg_boxed');
		}
		
		//Get Page Menu Transparent Option
		$page_menu_transparent = get_post_meta($post->ID, 'page_menu_transparent', true);
		if(!empty($page_menu_transparent))
		{
			$classes[] = esc_attr('tg_menu_transparent');
		}
	}
	
	//if password protected
	if(post_password_required() && is_page())
	{
	   	$classes[] = esc_attr('tg_password_protected');
	}
	
	//Get lightbox color scheme
	$tg_lightbox_color_scheme = get_theme_mod('tg_lightbox_color_scheme', 'black');
	
	if(!empty($tg_lightbox_color_scheme))
	{
		$classes[] = esc_attr('tg_lightbox_'.$tg_lightbox_color_scheme);
	}
	
	//Get sidemenu on desktop class
	$tg_sidemenu = get_theme_mod('tg_sidemenu', false);

	if(!empty($tg_sidemenu))
	{
		$classes[] = esc_attr('tg_sidemenu_desktop');
	}
	
	//Get main menu layout
	$tg_menu_layout = musico_menu_layout();
	if(!empty($tg_menu_layout))
	{
		$classes[] = esc_attr($tg_menu_layout);
	}
	
	//Get fotoer reveal class
	$tg_footer_reveal = get_theme_mod('tg_footer_reveal', false);
	if(!empty($tg_footer_reveal))
	{
		$classes[] = esc_attr('tg_footer_reveal');
	}

	return $classes;
}

//Now add test class to the filter
add_filter('body_class','musico_body_class_names');

add_filter('upload_mimes', 'musico_add_custom_upload_mimes');
function musico_add_custom_upload_mimes($existing_mimes) 
{
  	$existing_mimes['woff'] = 'application/x-font-woff';
  	return $existing_mimes;
}

add_action('init','musico_shop_sorting_remove');
function musico_shop_sorting_remove() {
	$tg_shop_filter_sorting = get_theme_mod('tg_shop_filter_sorting', true);
	
	if(empty($tg_shop_filter_sorting))
	{
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 30 );
		
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	}
}

add_action( 'admin_enqueue_scripts', 'musico_admin_pointers_header' );

function musico_admin_pointers_header() {
   if ( musico_admin_pointers_check() ) {
      add_action( 'admin_print_footer_scripts', 'musico_admin_pointers_footer' );

      wp_enqueue_script( 'wp-pointer' );
      wp_enqueue_style( 'wp-pointer' );
   }
}

function musico_admin_pointers_check() {
   $admin_pointers = musico_admin_pointers();
   foreach ( $admin_pointers as $pointer => $array ) {
      if ( $array['active'] )
         return true;
   }
}

function musico_admin_pointers_footer() {
   $admin_pointers = musico_admin_pointers();
?>
<script type="text/javascript">
/* <![CDATA[ */
( function($) {
   <?php
   foreach ( $admin_pointers as $pointer => $array ) {
      if ( $array['active'] ) {
         ?>
         $( '<?php echo esc_js($array['anchor_id']); ?>' ).pointer( {
            content: '<?php echo wp_kses_post($array['content']); ?>',
            position: {
            edge: '<?php echo esc_js($array['edge']); ?>',
            align: '<?php echo esc_js($array['align']); ?>'
         },
            close: function() {
               $.post( ajaxurl, {
                  pointer: '<?php echo esc_js($pointer); ?>',
                  action: 'dismiss-wp-pointer'
               } );
            }
         } ).pointer( 'open' );
         <?php
      }
   }
   ?>
} )(jQuery);
/* ]]> */
</script>
   <?php
}

function musico_admin_pointers() {
   $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
   $prefix = 'musico_admin_pointer';
   
   //Page help pointers
   $elementor_builder_content = '<h3>Page Builder</h3>';
   $elementor_builder_content .= '<p>Basically you can use WordPress visual editor to create page content but theme also has another way to create page content. By using Elementor Page Builder, you would be ale to drag&drop each content block without coding knowledge. Click here to enable Elementor.</p>';
   
   $page_options_content = '<h3>Page Options</h3>';
   $page_options_content .= '<p>You can customise various options for this page including menu styling, page templates etc.</p>';
   
   $page_featured_image_content = '<h3>Page Featured Image</h3>';
   $page_featured_image_content .= '<p>Upload or select featured image for this page to displays it as background header.</p>';
   
   //Post help pointers
   $post_options_content = '<h3>Post Options</h3>';
   $post_options_content .= '<p>You can customise various options for this post including its layout and featured content type.</p>';
   
   $post_featured_image_content = '<h3>Post Featured Image (*Required)</h3>';
   $post_featured_image_content .= '<p>Upload or select featured image for this post to displays it as post image on blog, archive, category, tag and search pages.</p>';

   $tg_pointer_arr = array(   
   	  //Page help pointers
      $prefix . '_elementor_builder' => array(
         'content' => $elementor_builder_content,
         'anchor_id' => '#elementor-switch-mode-button .elementor-switch-mode-off',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_elementor_builder', $dismissed ) )
      ),
      
      $prefix . '_page_options' => array(
         'content' => $page_options_content,
         'anchor_id' => 'body.post-type-page #page_option_page_menu_transparent',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_page_options', $dismissed ) )
      ),
      
      $prefix . '_page_featured_image' => array(
         'content' => $page_featured_image_content,
         'anchor_id' => 'body.post-type-page #set-post-thumbnail',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_page_featured_image', $dismissed ) )
      ),
      
      //Post help pointers
      $prefix . '_post_options' => array(
         'content' => $post_options_content,
         'anchor_id' => 'body.post-type-post #post_option_post_layout',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_post_options', $dismissed ) )
      ),
      
      $prefix . '_post_featured_image' => array(
         'content' => $post_featured_image_content,
         'anchor_id' => 'body.post-type-post #set-post-thumbnail',
         'edge' => 'top',
         'align' => 'left',
         'active' => ( ! in_array( $prefix . '_post_featured_image', $dismissed ) )
      ),
   );

   return $tg_pointer_arr;
}
?>
<?php
//Setup theme constant and default data
$theme_obj = wp_get_theme('musico');

define("MUSICO_THEMENAME", $theme_obj['Name']);
if (!defined('MUSICO_THEMEDEMO'))
{
	define("MUSICO_THEMEDEMO", false);
}
define("MUSICO_SHORTNAME", "pp");
define("MUSICO_THEMEVERSION", $theme_obj['Version']);
define("MUSICO_THEMEDEMOURL", $theme_obj['ThemeURI']);

define("THEMEGOODS_API", 'http://license.themegoods.com/manager/wp-json/envato');
define("THEMEGOODS_PURCHASE_URL", 'https://1.envato.market/jAnLn');

if (!defined('MUSICO_THEMEDATEFORMAT'))
{
	define("MUSICO_THEMEDATEFORMAT", get_option('date_format'));
}

if (!defined('MUSICO_THEMETIMEFORMAT'))
{
	define("MUSICO_THEMETIMEFORMAT", get_option('time_format'));
}

if (!defined('ENVATOITEMID'))
{
	define("ENVATOITEMID", 22841377);
}

//Get default WP uploads folder
$wp_upload_arr = wp_upload_dir();
define("MUSICO_THEMEUPLOAD", $wp_upload_arr['basedir']."/".strtolower(sanitize_title(MUSICO_THEMENAME))."/");
define("MUSICO_THEMEUPLOADURL", $wp_upload_arr['baseurl']."/".strtolower(sanitize_title(MUSICO_THEMENAME))."/");

if(!is_dir(MUSICO_THEMEUPLOAD))
{
	wp_mkdir_p(MUSICO_THEMEUPLOAD);
}

/**
*  Begin Global variables functions
*/

//Get default WordPress post variable
function musico_get_wp_post() {
	global $post;
	return $post;
}

//Get default WordPress file system variable
function musico_get_wp_filesystem() {
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	WP_Filesystem();
	global $wp_filesystem;
	return $wp_filesystem;
}

//Get default WordPress wpdb variable
function musico_get_wpdb() {
	global $wpdb;
	return $wpdb;
}

//Get default WordPress wp_query variable
function musico_get_wp_query() {
	global $wp_query;
	return $wp_query;
}

//Get default WordPress customize variable
function musico_get_wp_customize() {
	global $wp_customize;
	return $wp_customize;
}

//Get default WordPress current screen variable
function musico_get_current_screen() {
	global $current_screen;
	return $current_screen;
}

//Get default WordPress paged variable
function musico_get_paged() {
	global $paged;
	return $paged;
}

//Get default WordPress registered widgets variable
function musico_get_registered_widget_controls() {
	global $wp_registered_widget_controls;
	return $wp_registered_widget_controls;
}

//Get default WordPress registered sidebars variable
function musico_get_registered_sidebars() {
	global $wp_registered_sidebars;
	return $wp_registered_sidebars;
}

//Get default Woocommerce variable
function musico_get_woocommerce() {
	global $woocommerce;
	return $woocommerce;
}

//Get all google font usages in customizer
function musico_get_google_fonts() {
	$musico_google_fonts = array('tg_body_font', 'tg_header_font', 'tg_menu_font', 'tg_sidemenu_font', 'tg_sidebar_title_font', 'tg_button_font');
	
	global $musico_google_fonts;
	return $musico_google_fonts;
}

//Get menu transparent variable
function musico_get_page_menu_transparent() {
	global $musico_page_menu_transparent;
	return $musico_page_menu_transparent;
}

//Set menu transparent variable
function musico_set_page_menu_transparent($new_value = '') {
	global $musico_page_menu_transparent;
	$musico_page_menu_transparent = $new_value;
}

//Get no header checker variable
function musico_get_is_no_header() {
	global $musico_is_no_header;
	return $musico_is_no_header;
}

//Get deafult theme screen CSS class
function musico_get_screen_class() {
	global $musico_screen_class;
	return $musico_screen_class;
}

//Set deafult theme screen CSS class
function musico_set_screen_class($new_value = '') {
	global $musico_screen_class;
	$musico_screen_class = $new_value;
}

//Get theme homepage style
function musico_get_homepage_style() {
	global $musico_homepage_style;
	return $musico_homepage_style;
}

//Set theme homepage style
function musico_set_homepage_style($new_value = '') {
	global $musico_homepage_style;
	$musico_homepage_style = $new_value;
}

//Get page gallery ID
function musico_get_page_gallery_id() {
	global $musico_page_gallery_id;
	return $musico_page_gallery_id;
}

//Get default theme options variable
function musico_get_options() {
	global $musico_options;
	return $musico_options;
}

//Set default theme options variable
function musico_set_options($new_value = '') {
	global $musico_options;
	$musico_options = $new_value;
}

//Get top bar setting
function musico_get_topbar() {
	global $musico_topbar;
	return $musico_topbar;
}

//Set top bar setting
function musico_set_topbar($new_value = '') {
	global $musico_topbar;
	$musico_topbar = $new_value;
}

//Get is hide title option
function musico_get_hide_title() {
	global $musico_hide_title;
	return $musico_hide_title;
}

//Set is hide title option
function musico_set_hide_title($new_value = '') {
	global $musico_hide_title;
	$musico_hide_title = $new_value;
}

//Get theme page content CSS class
function musico_get_page_content_class() {
	global $musico_page_content_class;
	return $musico_page_content_class;
}

//Set theme page content CSS class
function musico_set_page_content_class($new_value = '') {
	global $musico_page_content_class;
	$musico_page_content_class = $new_value;
}

//Get Kirki global variable
function musico_get_kirki() {
	global $kirki;
	return $kirki;
}

//Get admin theme global variable
function musico_get_wp_admin_css_colors() {
	global $_wp_admin_css_colors;
	return $_wp_admin_css_colors;
}

//Get theme plugins
function musico_get_plugins() {
	global $musico_tgm_plugins;
	return $musico_tgm_plugins;
}

//Set theme plugins
function musico_set_plugins($new_value = '') {
	global $musico_tgm_plugins;
	$musico_tgm_plugins = $new_value;
}

$is_verified_envato_purchase_code = false;

//Get verified purchase code data
$pp_verified_envato_musico = get_option("pp_verified_envato_musico");
if(!empty($pp_verified_envato_musico))
{
	$is_verified_envato_purchase_code = true;
}

$is_imported_elementor_templates_musico = false;
$pp_imported_elementor_templates_musico = get_option("pp_imported_elementor_templates_musico");
if(!empty($pp_imported_elementor_templates_musico))
{
	$is_imported_elementor_templates_musico = true;
}
?>
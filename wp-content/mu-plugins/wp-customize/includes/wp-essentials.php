<?php
/*
Plugin Name: WordPress Essentials
Description: Cleans up the WordPress admin area and provides basic security and spam protection.
Author: Codecharmer Web Developers
Author URI: https://codecharmer.io
Version: 1.0
*/

add_action('after_setup_theme', 'wp_essentials_setup');
function wp_essentials_setup() {
	
	/*-----------------------------------------------------------------------------------*/
	// SOURCE 
	/*-----------------------------------------------------------------------------------*/

	// Remove unnecessary meta tags
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	
	// Removes WordPress version from styles and scripts
	add_filter('style_loader_src', 'remove_wp_version', 9999);
	add_filter('script_loader_src', 'remove_wp_version', 9999);
	function remove_wp_version($src) {
		if(strpos($src, 'ver='))
			$src = remove_query_arg('ver', $src);
		return $src;
	}

	// Remove wp_head() injected Recent Comment Styles
	add_action('widgets_init', 'my_remove_recent_comments_style');
	function my_remove_recent_comments_style()
	{
	    global $wp_widget_factory;
	    remove_action('wp_head', array(
	        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
	        'recent_comments_style'
	    ));
	}

	// Add browser specific body classes
	function custom_body_classes($classes){
	    // the list of WordPress global browser checks
	    // https://codex.wordpress.org/Global_Variables#Browser_Detection_Booleans
	    $browsers = ['is_iphone', 'is_chrome', 'is_safari', 'is_NS4', 'is_opera', 'is_macIE', 'is_winIE', 'is_gecko', 'is_lynx', 'is_IE', 'is_edge'];
	    // check the globals to see if the browser is in there and return a string with the match
	    $classes[] = join(' ', array_filter($browsers, function ($browser) {
	        return $GLOBALS[$browser];
	    }));
	    return $classes;
	}
	add_filter('body_class', 'custom_body_classes');

	// Add page slug to body class
	add_filter('body_class', 'add_slug_to_body_class');
	function add_slug_to_body_class($classes)
	{
	    global $post;
	    if (is_home()) {
	        $key = array_search('blog', $classes);
	        if ($key > -1) {
	            unset($classes[$key]);
	        }
	    } elseif (is_page()) {
	        $classes[] = sanitize_html_class($post->post_name);
	    } elseif (is_singular()) {
	        $classes[] = sanitize_html_class($post->post_name);
	    }
	    return $classes;
	}

	// Helper function to get assets
	function get_asset( $type, $file ) {
		return get_stylesheet_directory_uri() . '/assets/' . $type . '/' . $file;
	}

	// Remove Auto Paragraph
	//remove_filter ('the_content', 'wpautop');
	//remove_filter ('acf_the_content', 'wpautop');

	// Remove height/width attributes on images so they can be responsive
	add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
	add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );
	function remove_thumbnail_dimensions( $html ) {
	    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
	    return $html;
	}

	/*-----------------------------------------------------------------------------------*/
	// ADMIN STUFF
	/*-----------------------------------------------------------------------------------*/

	// Remove WordPress logo from admin bar
	add_action('wp_before_admin_bar_render', 'custom_admin_bar' );
	function custom_admin_bar() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}

	// Add shortcodes in widgets
	add_filter( 'widget_text', 'do_shortcode' );

	// Remove dashboard widgets
	add_action('admin_menu', 'remove_dashboard_widgets');
	function remove_dashboard_widgets(){
		remove_meta_box('dashboard_activity', 'dashboard', 'core'); // recent activity
		remove_meta_box('dashboard_right_now','dashboard','core'); // right now overview box
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'core'); // incoming links box
		remove_meta_box('dashboard_quick_press', 'dashboard', 'core'); // quick press box
		remove_meta_box('dashboard_plugins', 'dashboard', 'core'); // new plugins box
		remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core'); // recent drafts box
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // recent comments box
		remove_meta_box('dashboard_primary', 'dashboard', 'core'); // wordpress development blog box
		remove_meta_box('dashboard_secondary', 'dashboard', 'core'); // other wordpress news box
	}

	// Remove comment columns from pages
	add_filter('manage_pages_columns', 'custom_pages_columns');
	function custom_pages_columns($defaults) {
		unset($defaults['comments']); // comments
		return $defaults;
	}

	// Remove post meta boxes from screen options
	add_action('admin_menu','remove_post_metaboxes');
	function remove_post_metaboxes() {
		remove_meta_box('trackbacksdiv', 'post', 'normal');
	}

	// remove the "tags" column from the post list
	add_filter('manage_posts_columns' , 'update_post_columns');
	function update_post_columns($columns) {
		unset( $columns['tags'] );
		return $columns;
	}

	// Remove page meta boxes from screen options
	add_action('admin_menu', 'remove_page_metaboxes');
	function remove_page_metaboxes() {
		remove_meta_box('commentstatusdiv', 'page', 'normal');
		remove_meta_box('commentsdiv', 'page', 'normal');
	}

	// Removes unnecessary user profile fields  
	add_filter('user_contactmethods', 'hide_profile_fields', 10, 1);
	function hide_profile_fields($contactmethods) {
		unset($contactmethods['aim']);
		unset($contactmethods['jabber']);
		unset($contactmethods['yim']);
		return $contactmethods;
	}

	add_action( 'admin_head-user-edit.php', 'remove_website_row_wpse_dte_css' );
	add_action( 'admin_head-profile.php',   'remove_website_row_wpse_dte_css' );
	function remove_website_row_wpse_dte_css() {
    	echo '<style>
    		tr.user-url-wrap,
    		tr.user-description-wrap
    		{ display: none; }
    	</style>';
	}

	// Removes color scheme options from user profiles
	/*add_action('admin_head', 'remove_color_scheme');
	function remove_color_scheme() {
		global $_wp_admin_css_colors;
		$_wp_admin_css_colors = 0;
	}*/

	// Hides updates from non-admins
	add_action('admin_menu', 'essentials_remove_update_nag');
	function essentials_remove_update_nag() {
		if ( !current_user_can('update_options')) {
			remove_action('admin_notices', 'update_nag', 3);
		}
	}

	// Disables self-trackbacking
	add_action('pre_ping', 'disable_self_pings');
	function disable_self_pings($links) {
		foreach ($links as $l => $link)
			if (0 === strpos($link, home_url()))
				unset($links[$l]);
	}


	// Remove the ability to delete a pages from the page list
	/*add_filter( 'page_row_actions', 'remove_delete_link_from_page_list', 10, 2 );
	function remove_delete_link_from_page_list( $actions ){

		// Front Page
		if(get_the_ID() === '') {
			unset( $actions[ 'delete' ] );
			unset( $actions['inline hide-if-no-js']);
			//$actions['trash'] = 'hello';
		}

		return $actions;
	} */

	// Custom Login Screen
	function custom_login() {
		wp_enqueue_style( 'custom-login', get_bloginfo('url') . '/wp-content/mu-plugins/wp-customize/css/login.css?v=1.3' );
		wp_enqueue_script( 'custom-login', get_bloginfo('url') . '/wp-content/mu-plugins/wp-customize/js/login.js' );
	}
	add_action('login_enqueue_scripts', 'custom_login');

	function my_login_logo_url() {
		return home_url();
	}
	add_filter( 'login_headerurl', 'my_login_logo_url' );

	function my_login_logo_url_title() {
		return get_option('blogname');
	}
	add_filter( 'login_headertitle', 'my_login_logo_url_title' );

	// Customize admin footer
	function modify_footer_admin () {
	  echo 'Created by <a href="https://codecharmer.io">Codecharmer</a>.<br> ';
	  echo 'For development needs please email the <i class="fa fa-envelop"></i> <a href="mailto:dev@codecharmer.io">Codecharmer Dev Team</a>.';
	}
	add_filter('admin_footer_text', 'modify_footer_admin');

	// Admin Styles
	/*function dte_admin_styles() {
		wp_enqueue_style( 'admin-css', get_asset( 'css', 'admin-styles.css' ) );
	}
	add_action( 'admin_print_styles', 'dte_admin_styles' ); */

	/*-----------------------------------------------------------------------------------*/
	// Admin Protection
	/*-----------------------------------------------------------------------------------*/
	if (is_admin()) {
		function essentials_block_admin() {
			// If the user is not an administrator, kill WordPress execution and provide a message
			if (!current_user_can('manage_categories') && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php') {
				wp_die(__('You are not allowed to access this part of the site'));
			}
		}
		add_action('admin_init', 'essentials_block_admin', 1);
	}

	// Hide theme editor
	if(!defined('DISALLOW_FILE_EDIT')) {
		define('DISALLOW_FILE_EDIT', 'true');
	}

	// Protect against malicious URL requests
	global $user_ID; if($user_ID) {
		if(!current_user_can('administrator')) {
			if (strlen($_SERVER['REQUEST_URI']) > 255 ||
				stripos($_SERVER['REQUEST_URI'], "eval(") ||
				stripos($_SERVER['REQUEST_URI'], "CONCAT") ||
				stripos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||
				stripos($_SERVER['REQUEST_URI'], "base64")) {
					@header("HTTP/1.1 414 Request-URI Too Long");
					@header("Status: 414 Request-URI Too Long");
					@header("Connection: Close");
					@exit;
			}
		}
	}

	// Reduce spam by banning empty referrers
	add_action('check_comment_flood', 'verify_comment_referrer');
	function verify_comment_referrer() {
		if (!wp_get_referer()) {
			wp_die(__('You cannot post a comment at this time. Maybe you need to enable referrers in your browser.'));
		}
	}

	/*-----------------------------------------------------------------------------------*/
	// ALLOW SVG
	/*-----------------------------------------------------------------------------------*/

	function cc_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	add_filter('upload_mimes', 'cc_mime_types');

	/*-----------------------------------------------------------------------------------*/
	// EDITOR
	/*-----------------------------------------------------------------------------------*/

	// Disable emojis
	add_action( 'init', 'disable_emojis' );
	function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	}

	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
	
	// Editor Styles
	/*function custom_editor_styles() {
		add_editor_style( ''.get_template_directory_uri().'/assets/css/admin-styles.css');
	}
	add_action( 'admin_init', 'custom_editor_styles' ); */

	/*-----------------------------------------------------------------------------------*/
	// SITE FUNCTIONALITY
	/*-----------------------------------------------------------------------------------*/

	// Make WordPress to Stop Guessing URLS
	// If you write the URL of a page in a WordPress site incorrectly, WordPress will try to guess what page you were trying to access and “fix” your request so that you get the proper page and not a 404 error
	add_filter('redirect_canonical', 'stop_guessing');
	function stop_guessing($url) {
	 if (is_404()) {
	   return false;
	 }
	 return $url;
	}

	/*-----------------------------------------------------------------------------------*/
	// Remove 'text/css' from our enqueued stylesheet
	// HTML5 spec indicates that the type attribute is no longer needed for the link, style, and script elements.
	/*-----------------------------------------------------------------------------------*/
	add_filter('style_loader_tag', 'dte_style_remove');
	function dte_style_remove($tag)
	{
	    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
	}

	/*-----------------------------------------------------------------------------------*/
	// Sets the post excerpt length to any number of characters.
	// To override this length in a child theme, remove the filter and add your own
	// function tied to the excerpt_length filter hook.
	/*-----------------------------------------------------------------------------------*/
	// add_filter('the_excerpt', 'excerpt');
	// function excerpt($limit) {
	//      $excerpt = explode(' ', get_the_excerpt(), $limit);
	//      if (count($excerpt)>=$limit) {
	//      array_pop($excerpt);
	//      $excerpt = implode(" ",$excerpt).'';
	//      } else {
	//      $excerpt = implode(" ",$excerpt);
	//      }
	//      $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
	//      return $excerpt;
	// }

	/*-----------------------------------------------------------------------------------*/
	// Returns a "Continue Reading" link for excerpts
	/*-----------------------------------------------------------------------------------*/
	/*if ( !function_exists( 'dte_continue_reading_link' ) ) {
		function dte_continue_reading_link() {
			//return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'smpl' ) . '</a>';
		}
	}*/

	/*-----------------------------------------------------------------------------------*/
	// Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis
	// and dte_continue_reading_link().
	//
	// To override this in a child theme, remove the filter and add your own
	// function tied to the excerpt_more filter hook.
	/*-----------------------------------------------------------------------------------*/
	/*if ( !function_exists( 'dte_auto_excerpt_more' ) ) {
		function dte_auto_excerpt_more( $more ) {
			return ' &hellip;' . dte_continue_reading_link();
		}
		add_filter( 'excerpt_more', 'dte_auto_excerpt_more' );
	}*/

	/*-----------------------------------------------------------------------------------*/
	// Adds a pretty "Continue Reading" link to custom post excerpts.
	/*-----------------------------------------------------------------------------------*/
	/*if ( !function_exists( 'dte_custom_excerpt_more' ) ) {
		function dte_custom_excerpt_more( $output ) {
			if ( has_excerpt() && ! is_attachment() ) {
				$output .= dte_continue_reading_link();
			}
			return $output;
		}
		add_filter( 'get_the_excerpt', 'dte_custom_excerpt_more' );
	}*/

	/*-----------------------------------------------------------------------------------*/
	// Removes the page jump when read more is clicked through
	/*-----------------------------------------------------------------------------------*/
	if ( !function_exists( 'remove_more_jump_link' ) ) {
		function remove_more_jump_link($link) {
			$offset = strpos($link, '#more-');
			if ($offset) {
			$end = strpos($link, '"',$offset);
			}
			if ($end) {
			$link = substr_replace($link, '', $offset, $end-$offset);
			}
			return $link;
		}
		add_filter('the_content_more_link', 'remove_more_jump_link');
	}

	/*-----------------------------------------------------------------------------------*/
	//remove pages
	/*-----------------------------------------------------------------------------------*/
	function dte_remove_menus(){
	  //remove_menu_page( 'index.php' );                  	//Dashboard
	  //remove_menu_page( 'edit.php' );                   	//Posts
	  //remove_menu_page( 'upload.php' );                 	//Media
	  //remove_menu_page( 'edit.php?post_type=page' );    	//Pages
	  //remove_menu_page( 'edit-comments.php' );          	//Comments
	  //remove_menu_page( 'themes.php' );                 	//Appearance
	  //remove_menu_page( 'plugins.php' );              	//Plugins
	  //remove_menu_page( 'users.php' );                  	//Users
	  //remove_menu_page( 'tools.php' );                  	//Tools
	  //remove_menu_page( 'options-general.php' );        	//Settings
	  remove_menu_page( 'about.php' );
	  remove_menu_page( 'edit-tags.php' );

	  $currentuserID = get_current_user_id();

	  if ($currentuserID !== 1) {
	  	//remove_menu_page( 'edit.php?post_type=acf-field-group' );
	  	//remove_menu_page( 'options-general.php' );
	  }
	  if ($currentuserID !== 1 && $currentuserID !== 2) {
	  	//remove_menu_page( 'tools.php');
	  }
	}
	add_action( 'admin_menu', 'dte_remove_menus', 999 );

	/*-----------------------------------------------------------------------------------*/
	// WP MENU
	/*-----------------------------------------------------------------------------------*/

	// Remove the <div> surrounding the dynamic navigation to cleanup markup
	/*add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args');
	function my_wp_nav_menu_args($args = '')
	{
	    $args['container'] = false;
	    return $args;
	} */

	//	Remove injected classes, IDs and Page IDs from Navigation <li> items
	//	It keeps the 'current' menu class but changes it to 'active' and removes the others
	/*function custom_wp_nav_menu($var) {
        return is_array($var) ? array_intersect($var, array(
                //List of allowed menu classes
                'current_page_item',
                'current_page_parent',
                'current_page_ancestor',
                'first',
                'last',
                'vertical',
                'horizontal'
                )
        ) : '';
	}
	add_filter('nav_menu_css_class', 'custom_wp_nav_menu');
	add_filter('nav_menu_item_id', 'custom_wp_nav_menu');
	add_filter('page_css_class', 'custom_wp_nav_menu'); */

	// Replaces "current-menu-item" with "active"
	/*function current_to_active($text){
	        $replace = array(
	                //List of menu item classes that should be changed to "active"
	                'current_page_item' => 'active',
	                'current_page_parent' => 'active',
	                'current_page_ancestor' => 'active',
	        );
	        $text = str_replace(array_keys($replace), $replace, $text);
	                return $text;
	        }
	add_filter ('wp_nav_menu','current_to_active');*/

	// Delete empty classes and removes the sub menu class
	/*function strip_empty_classes($menu) {
	    $menu = preg_replace('/ class=""| class="sub-menu"/','',$menu);
	    return $menu;
	}
	add_filter ('wp_nav_menu','strip_empty_classes'); */
}
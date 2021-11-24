<?php global $blog_id;
	
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme Content',
		'menu_title'	=> 'Theme Content',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Alert Bar',
		'menu_title'	=> 'Alert Bar',
		'parent_slug'	=> 'theme-general-settings'
	));

}

<?php 
/*-----------------------------------------------------------------------------------*/
// ADD A HELP PAGE
/*-----------------------------------------------------------------------------------*/
add_filter( 'contextual_help', 'dte_remove_help', 999, 3 );
function dte_remove_help($old_help, $screen_id, $screen){
	//$whitelist = array('admin.php', 'post-new.php');
	$screen = get_current_screen();
	if ($screen->base === 'toplevel_page_getting-help') {
    	return false;
    }
    $screen->remove_help_tabs();
    return $old_help;

}

add_action('admin_menu', 'dte_help_page');
function dte_help_page() {
	global $wp_meta_boxes;
	$dte_help_page = add_menu_page(
		'Getting Help',
		'Getting Help',
		'manage_options',
		'getting-help',
		'rhp_help_callback',
		'dashicons-welcome-learn-more',
		'1'
	);
	add_action( 'load-'.$dte_help_page, 'rhp_add_help_tab' );
}


function rhp_add_help_tab () {
	$screen = get_current_screen();

	$screen->add_help_tab( array(
		'id'		=> 'rhp-help',
		'title'		=> 'Introduction',
		'content'	=> '
						<h1>Welcome to your new site</h1>
						<!--<script src="//fast.wistia.com/embed/medias/cc6bfce881.jsonp" async></script>
						<script src="//fast.wistia.com/assets/external/E-v1.js" async></script>
						<div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_cc6bfce881 videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>
						-->
						<p>Welcome to the Valleywise Health Help Section. Check out the tabs to find information on the various sections of the site.</p>
						<p>If you have any other questions be sure to email <a href="mailto:dev@codecharmer.io">Codecharmer Dev Team</a>.</p>
						<p>&nbsp;</p>
						'
		//'callback' => 'pwh_help_callback'
	) );
	$screen->add_help_tab( array(
            'id'    	=> 'homepage-tab',
            'title' 	=> 'Home Page',
            'content' 	=> '
            				<h1>The Home Page</h1>
            				<p>The homepage has many different sections that are handled through the <strong>"Pages" - "Welcome"</strong> page.</p>
							<p>The homepage hero has the ability to have two art directed hero images. One for desktop and one for mobile.</p>
							<p>The <strong>TRIO SECTION</strong> are handled through the "Welcome" page</p>
							<p>The <strong>ABOUT SECTION</strong> excerpt and quote section is handled through the <a href="/wp-admin/post.php?post=6&action=edit">about page</a>.
							<p>For the <strong>VIDEO SECTION</strong>, you are able to change out the title, subtitle, video cover image, the youtube video ID, the button text and where the button links to.</p>
							<p>The <strong>SHOP SECTION</strong> of the homepage will allow for a cover image, title and copy, as well as a separate image for the products. There is a section for a CTA button, but that is turned off at this time.</p>
							<p>&nbsp;</p>
							'
    ) );

    $screen->add_help_tab( array(
            'id'    	=> 'pages-tab',
            'title' 	=> 'Pages',
            'content' 	=> '
            				<h1>Static Pages</h1>
            				<p>Static pages have the ability to art direct the hero images by having a desktop and mobile version.</p>
							<p>The <strong><a href="/wp-admin/post.php?post=6&action=edit">ABOUT</a></strong> page also has custom fields for the excerpt and the quote that appear on the homepage.</p>
							<p>The <strong>MUSIC</strong> page is a placeholder template for the MUSIC CUSTOM POST TYPE. See the next tab for the MUSIC help section.</p>
							<p>The <strong>NEWS</strong> page will handle several things.</p>
							<ul><li>The <a href="/wp-admin/post.php?post=9&action=edit">news excerpt</a> that is on the homepage</li><li>The posts that are in the slider section on the NEWS page</li></ul>
							<p>To add a post to the slider section, just go to the <a href="/wp-admin/post.php?post=9&action=edit">NEWS PAGE</a> and add any post to the NEWS SLIDER. A maximum of 5 posts will be allowed.</p>
							<p>To write articles go to the <a href="/wp-admin/edit.php">NEWS</a> section.</p>
							<p>&nbsp;</p>
							'
    ) );

    $screen->add_help_tab( array(
            'id'    	=> 'category-pages-tab',
            'title' 	=> 'Music',
            'content' 	=> '
            				<h1>How to add MUSIC</h1>
            				<p>Each individual music item will need to be added through the <a href="/wp-admin/edit.php?post_type=music">MUSIC CUSTOM POST TYPE</a>.</p>
            				<p>Each MUSIC ITEM will need cover art work, a title, and release date.</p>
    						<p>Optionally, tracks and purchase links can be added.</p>
    						<h3 style="margin-top: 20px;">Music Category Filters</h3>
    						<p>For each MUSIC ITEM make sure to add a MUSIC TYPE and MUSIC YEAR filter category. These are used to filter on the front end of the website.</p>
    						<p>&nbsp;</p>
    						'
    ) );

    $screen->add_help_tab( array(
            'id'    	=> 'theme-settings-tab',
            'title' 	=> 'Theme Settings',
            'content' 	=> '
            				<h1>How to use the Theme Settings</h1>
            				<h2>Main Settings</h2>
            				<p>These are the following options can can be changed thru the <a href="/wp-admin/admin.php?page=theme-general-settings">Theme Settings</a>:</p>
            				<ul>
            				<li>Header Logo</li>
            				<li>The Footer Background Image</li>
            				<li>TThe Footer Copyright</li>
            				<li>The Newsletter Signup Text, Mailchimp List ID and API key</li>
            				<li>The email address for the Client Contact</li>
            				<li>The Social Network URLs</li>
            				</ul>

            				<p>&nbsp;</p>
            				'
    ) );

	$screen->add_help_tab( array(
            'id'    	=> 'posts-tab',
            'title' 	=> 'Articles',
            'content' 	=> '
            				<h1>How to Add an Article</h1>
            				<p>Articles are handled thru the <a href="/wp-admin/edit.php">NEWS</a> section in the left navigation bar.</p>
							<p>Click the <a href="/wp-admin/post-new.php">Add New</a> button at the top.</p>
							<p>Fill out the title, the content area, add an excerpt. Optionally you can add a "Read More Text". This will create the button when the post sits in the sidebar.</p>
							<p>In the right sidebar of the post creation page you will need to pick a "Category". The "Featured News" will display in a landscape version on the homepage and the main News page.</p>
							<p>If you need to create a category, make sure you create a <a href="/wp-admin/edit-tags.php?taxonomy=category">News Category</a>.</p>

							<p>Be sure to pick a FEATURED IMAGE for each article. The image should be at least 910px x 510px.</p>
							<p>&nbsp;</p>
							'
    ) );

    $screen->add_help_tab( array(
            'id'    	=> 'maintenance-tab',
            'title' 	=> 'Maintenance Mode',
            'content' 	=> '
            				<h1>How to Enable Maintenance Mode</h1>
            				<p>The <a href="/wp-admin/admin.php?page=maintenance_mode">MAINTENANCE MODE</a> is used to lock down the WordPress admin.</p>
            				<p>This can be used when updating the site, plugins, or when an admin needs to go in to do routine maintenance.<p>
            				<p>To enable, click on the Maintenance Mode in the left sidebar. Select the superAdmin and the maintenance mode page, and if you want to shut down the admin.</p>
            				<p>Once the admin is shut down, any user trying to log in will be redirected to the Maintenance page, where you can have a custom message.</p>
            				<p>Upon entering and exiting maintenance mode, users will be email that the admin is either shutdown or back up.</p>
							<p>&nbsp;</p>
							'
    ) );


	$screen->set_help_sidebar(
            '<p>Quick Links</p>
            <p><a href="/wp-admin/admin.php?page=theme-general-settings">Theme Settings Main</a></p>
            <p><a href="/wp-admin/nav-menus.php">Main Menu Page</a></p>
            <p><a href="/wp-admin/admin.php?page=maintenance_mode">Maintenance Mode</a></p>
            '
    );
}

function rhp_help_callback(  ){
   echo '<script>
	       jQuery(window).load(function(){
				"use strict";
			 	jQuery("#contextual-help-link").click();
			});
		</script>';
}




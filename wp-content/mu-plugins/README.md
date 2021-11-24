# MU Plugins

A base mu-plugin created for custom WP website builds. This documentation is still in progress. Currently working through wp-essentials.php from line 354: Remove the div surrounding the dynamic navigation to cleanup markup

## Custom Post Types ##
*Source File: wp-customize/includes/custom-post-types/*

Add custom post types and their taxonomies into individual php files within this directory for easy management.

## Gutenberg ##
*Source File: wp-customize/includes/gutenberg/*

Manage blocks and disable Gutenberg on specific pages and/or page templates.

## ACF Options ##
*Source File: wp-customize/includes/acf-options.php*

Add option pages using Advanced Custom Fields

## Help Page ##
*Source File: wp-customize/includes/help-page.php*

Enables the addition of a help/documentation page accessible from the WordPress admin menu (disabled)

## Essential Functions ##
*Source File: wp-customize/includes/wp-essentials.php*

**Source**

* Removes unnecessary meta tags
* Removes WordPress version from styles and scripts
* Removes wp_head() injected recent comment styles
* Adds browser specific body classes
* Adds page slug to body class
* Helper function to get assets
* Removes auto paragraph (disabled)
* Remove static height/width attributes on images

**WP Admin Stuff**

* Removes WordPress logo from admin bar
* Adds shortcodes in widgets
* Removes dashboard widgets 
* Removes comment columns from pages
* Removes post meta boxes from screen options
* Remove the "tags" column from the post list
* Removes page meta boxes from screen options
* Removes unnecessary user profile fields 
* Removes color scheme options (disabled)
* Hides updates from non-admins
* Disables self-trackbacking
* Remove the ability to delete a pages from the page list
* Adds custom login screen *(wp-customize/css/login.css and js/login.js for additional assets)*
* Customize admin footer
* Adds specific admin styles (disabled)
* Adds specific editor styles (disabled)

**Admin Protection**

* Hide theme editor
* Protect against malicious URL requests
* Reduce spam by banning empty referrers

**Allow SVG Uploads**

* Allows SVG to be uploaded to media library

**Editor**

* Disables emojis
* Adds specific editor styles (disabled)

**WP Menu**

* Remove the div surrounding the dynamic navigation to cleanup markup 
(disabled)
* Remove injected classes, IDs and Page IDs from Navigation (disabled)
* Replaces "current-menu-item" with "active" (disabled)
* Delete empty classes and removes the sub menu class (disabled)

**Site Functionality**

* Makes WordPress stop guessing URLs

### How do I get set up? ###

* Add mu-plugins folder to wp-content
* Add and remove functionality in options.php file

### Who do I talk to? ###

* dev@codecharmer.io
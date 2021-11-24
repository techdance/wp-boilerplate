<?php
/**
 * Plugin Name: Codecharmer Options & Content (ROC)
 * Plugin URI: https://codecharmer.io
 * Description: Clean up WordPress Admin
 * Version: 0.1
 * Author: The AMAZING Developers at Codecharmer Creative Group
 * Author URI: https://codecharmer.io
 *
 * @package   CodecharmerOptionsContent
 * @version   0.1.0
 * @since     0.1.0
 * @author    Codecharmer Web Developers <dev@codecharmer.io>
 * @copyright Copyright (c) 2014, codecharmer.io
 * @link      https://codecharmer.io
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class wp_customize_options {

	/**
	 * PHP5 constructor method.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( &$this, 'includes' ), 3 );

		/* Load the admin files. */
		// this handles the maintenance options
		add_action( 'plugins_loaded', array( &$this, 'admin' ), 4 );

		/* Register activation hook. */
		register_activation_hook( __FILE__, array( &$this, 'activation' ) );
	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function constants() {

		/* Set constant path to the plugin directory. */
		define( 'ROC_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set the constant path to the plugin directory URI. */
		define( 'ROC_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the includes directory. */
		define( 'ROC_INCLUDES', ROC_DIR . trailingslashit( 'includes' ) );

		/* Set the constant path to the admin directory. */
		define( 'ROC_ADMIN', ROC_DIR . trailingslashit( 'maintenance-mode' ) );

	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function includes() {
		// Essential Functions
		require_once( ROC_INCLUDES . 'wp-essentials.php' );
		
		// Add Help Page
		//require_once( ROC_INCLUDES . 'help-page.php' );

		// ACF Options Pages
		//require_once( ROC_INCLUDES . 'acf-options.php' );

		//load all the custom post types
		require_once( ROC_INCLUDES . 'custom-post-types/locations.php' );
		require_once( ROC_INCLUDES . 'custom-post-types/team.php' );
		require_once( ROC_INCLUDES . 'custom-post-types/testimonials.php' );

		// gutenberg / default editor options
		// require_once( ROC_INCLUDES . 'gutenberg/blocks.php' );
		require_once( ROC_INCLUDES . 'gutenberg/disable.php' );
	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function admin() {

		if ( is_admin() ) {

			// Something in this file is breaking Disable Gutenburg plugin
			//require_once( ROC_ADMIN . 'maintenance-mode.php' );
		}
	}




}

new wp_customize_options();

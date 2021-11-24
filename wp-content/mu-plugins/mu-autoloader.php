<?php
/**
 * Plugin Name: Codecharmer MU Autoloader
 * Plugin URI: https://codecharmer.io
 * Description: MU Options for all Codecharmer WP Theme's
 * Version: 0.1.0
 * Author: The AMAZING Codecharmer Web Dev Team!
 * Author URI: https://codecharmer.io
 *
 * @package   CodecharmerMULoader
 * @version   0.1.0
 * @since     0.1.0
 * @author    Codecharmer Developers <dev@codecharmer.io>
 * @copyright Copyright (c) 2019, codecharmer.io
 * @link      https://codecharmer.io
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// load the plugins
require WPMU_PLUGIN_DIR . '/acf-styles/acf-styles.php';
require WPMU_PLUGIN_DIR . '/wp-customize/options.php';

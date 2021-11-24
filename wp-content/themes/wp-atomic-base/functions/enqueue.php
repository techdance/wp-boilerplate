<?php

// Main Styles & Scripts - Manifest File Data
function theme_files($hashFile) {
	/**
	 * Read the manifest file created by 'npm run build'
	 *
	 * @param $manifestFilename
	 *
	 * @return array|mixed|object
	 */
	function readManifest($manifestFilename) {
		$manifestFile= fopen($manifestFilename, 'r') or die('Unable to open manifest file!');
		$manifestFileContents =  fread($manifestFile, filesize($manifestFilename));
		fclose($manifestFile);

		return json_decode($manifestFileContents, true); // decode the JSON manifest
	}

	$manifestFileData = readManifest(get_stylesheet_directory() . '/dist/manifest.json');
	
	$mainCSS = $manifestFileData['main.css'];
	wp_enqueue_style( 'theme-styles', get_template_directory_uri() . '/dist/'.$mainCSS);

	$mainJS = $manifestFileData['main.js'];
	wp_register_script('main', get_template_directory_uri() . '/dist/'. $mainJS, '', NULL, true);
	wp_enqueue_script('main');
}
add_action( 'wp_enqueue_scripts', 'theme_files' );

// Bootstrap Homie
function bootstrap($hashFile) {
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', '//code.jquery.com/jquery-3.4.1.min.js', array(), null, true);
	wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', array(), null, true);
	wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', array(), null, true);
}
add_action( 'wp_enqueue_scripts', 'bootstrap' );

function externals() {
	// Splide - JS Slider
	wp_enqueue_style( 'splide', get_template_directory_uri() . '/node_modules/@splidejs/splide/dist/css/splide.min.css');
}
add_action( 'wp_enqueue_scripts', 'externals' );

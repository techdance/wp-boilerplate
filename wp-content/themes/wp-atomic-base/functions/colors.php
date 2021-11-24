<?php 	
/*************************************************************/
/*  Custom color swatches in Gutenburg blocks */
/***********************************************************/

function gb_colors() {
	// Disable Custom Colors
	add_theme_support( 'disable-custom-colors' );
  
	// Editor Color Palette
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'Black', 'gb-color' ),
			'slug'  => 'black',
			'color'	=> '#000000',
		),
		array(
			'name'  => __( 'Dark Grey', 'gb-color' ),
			'slug'  => 'darkgrey',
			'color' => '#888888',
		),
	) );
}
add_action( 'after_setup_theme', 'gb_colors' );

/*************************************************************/
/*  Custom color swatches in Wysiwyg */
/***********************************************************/

function my_mce4_options($init) {

  $custom_colors = '
      "000000", "Black",
      "888888", "Dark Grey"
  ';

  // build color grid default+custom colors
  $init['textcolor_map'] = '['.$custom_colors.']';

  // change the number of rows in the grid if the number of colors changes
  // 8 swatches per row
  $init['textcolor_rows'] = 1;

  return $init;
}
add_filter('tiny_mce_before_init', 'my_mce4_options');


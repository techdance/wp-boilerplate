<?php 
/**
 * Block Management
 *
 */

add_filter( 'allowed_block_types', 'wp_allowed_block_types', 10, 2 );
 
function wp_allowed_block_types( $allowed_blocks, $post ) {
 
	$allowed_blocks = array(
		// Common
		'core/paragraph',
		'core/image',
		'core/gallery',
		'core/heading',
		'core/quote',
		'core/list',
		'core/cover',
		'core/video',
		'core/audio',

		// Formatting
		'core/table',
		'core/verse',
		'core/code',
		'core/freeform',
		'core/html',
		'core/preformatted',
		'core/pullquote',
		
		// Layout Elements
		'core/button',
		'core/text-columns',
		'core/media-text',
		'core/more',
		'core/nextpage',
		'core/separator',
		'core/spacer',
		
		// ACF Blocks
		// Import json: template-parts > blocks > json > acf-export-blockname.json
		'acf/testimonial'
	);
	
	// Show blocks on page templates
	if(get_page_template_slug( $post->ID ) === 'template-name.php') {
		//$allowed_blocks[] = 'acf/testimonial';
	}

	// Show blocks on post types
	if( $post->post_type === 'post' ) {
		//$allowed_blocks[] = 'core/shortcode';
	}
 
	return $allowed_blocks;
 
}


/**
 * Adding ACF Blocks
 *
 */
	
add_action('acf/init', 'acf_blocks_init');
function acf_blocks_init() {
	
	// check function exists
	if( function_exists('acf_register_block') ) {
		
		// register a testimonial block
		acf_register_block(array(
			'name'				=> 'testimonial',
			'title'				=> __('Testimonial'),
			'description'		=> __('A custom testimonial block.'),
			'render_callback'	=> 'acf_blocks_render_callback',
			'category'			=> 'formatting',
			'icon'				=> 'admin-comments',
			'keywords'			=> array( 'testimonial', 'quote' ),
		));
	}
}

function acf_blocks_render_callback( $block ) {
	
	// convert name ("acf/testimonial") into path friendly slug ("testimonial")
	$slug = str_replace('acf/', '', $block['name']);
	
	// include a template part from within the "template-parts/block" folder
	if( file_exists( get_theme_file_path("/template-parts/blocks/content-{$slug}.php") ) ) {
		include( get_theme_file_path("/template-parts/blocks/content-{$slug}.php") );
	}
}
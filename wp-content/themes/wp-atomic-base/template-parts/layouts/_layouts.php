<?php // If Gutenberg (see mu-plugins) is disabled and page has layouts
if( have_rows('layouts') ):
    while ( have_rows('layouts') ) : the_row();

		$layout = get_row_layout();

			switch($layout):			
				
				// Accordion
				case 'accordion':
					get_template_part( 'template-parts/layouts/accordion' );
					break;
				
				// Content Fullwidth
				case 'content_full':
					get_template_part( 'template-parts/layouts/content-full' );
					break;
			
				// Counter
				case 'counter':
					get_template_part( 'template-parts/layouts/counter' );
					break;

				// CTA Fullwidth
				case 'cta_full':
					get_template_part( 'template-parts/layouts/cta-full' );
					break;

				// Callout - Call To Action
				case 'cta_callout':
					get_template_part( 'template-parts/layouts/callout', 'cta' );
					break;

				// Callout - Heading / Text Block (2 Columns)
				case 'callout_txtblock_cols':
					get_template_part( 'template-parts/layouts/callout', 'txtblock-cols' );
					break;
								
				// Card - Image Blocks
				case 'card_img_blocks':
					get_template_part( 'template-parts/layouts/card', 'img-blocks' );
					break;

				// Document Downloads
				case 'doc_download':
					get_template_part( 'template-parts/layouts/doc-downloads' );
					break;

				// Form (BG Image / Offset)
				case 'form_bg_img_offset':
					get_template_part( 'template-parts/layouts/form', 'bg-img-offset' );
					break;
									
				// Icon Row
				case 'icon_row':
					get_template_part( 'template-parts/layouts/icon-row' );
					break;
									
				// Icon + Text Block (2 Columns)
				case 'txtblock_icon_2col':
					get_template_part( 'template-parts/layouts/txtblock-icon', '2col' );
					break;

				// Image (Contained / Fullwidth)
				case 'img_contained_full':
					get_template_part( 'template-parts/layouts/img-contained-full' );
					break;

				// Instagram Feed
				case 'ig_feed':
					get_template_part( 'template-parts/layouts/feed', 'instagram' );
					break;
								
				// Logo Blocks
				case 'logo_blocks':
					get_template_part( 'template-parts/layouts/logo', 'blocks' );
					break;

				// Team Bios
				case 'team_bios':
					// Pagelink - links out to team bio page
					// get_template_part( 'template-parts/layouts/team-bios', 'pagelink' );

					// Flexrow - displays bio on page inbetween bio rows
					get_template_part( 'template-parts/layouts/team-bios', 'flexrow' );
					break;
				
				// Testimonial Blocks
				case 'testimonial_blocks':
					get_template_part( 'template-parts/layouts/testimonial', 'blocks' );
					break;
			
				// Testimonial Slider
				case 'testimonial_slider':
					get_template_part( 'template-parts/layouts/testimonial', 'slider' );
					break;	
					
				// Text Block + BG Image
				case 'txtblock_bg_img':
					get_template_part( 'template-parts/layouts/txtblock', 'bg-img' );
					break;	
				
				// Text Block + Image
				case 'txtblock_img':
					get_template_part( 'template-parts/layouts/txtblock', 'img' );
					break;	
			
				// Text Block + Image (Offset Slider)
				case 'txtblock_img_offset_slider':
					get_template_part( 'template-parts/layouts/txtblock', 'img-offset' );
					break;	
				
				// Text Blocks (2 Columns)
				case 'txtblocks_col':
					get_template_part( 'template-parts/layouts/txtblocks', 'col' );
					break;
				
				// Video Contained (Blog)
				// no container options - will always be contained - used in blog templates
				case 'video_contained':
					get_template_part( 'template-parts/layouts/video', 'contained' );
					break;
			
				// Video Featured
				// has container options - used in main content templates
				case 'video_featured':
					get_template_part( 'template-parts/layouts/video', 'featured' );
					break;	

			endswitch;

    endwhile;

else : // else show the content

    the_content();

endif;?>



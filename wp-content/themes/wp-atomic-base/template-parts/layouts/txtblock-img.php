<?php // Text Block + Image

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// display options (card or fullwidth)
include get_template_directory() . '/template-parts/options/options-display.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php';

// layout options: sets layout flex direction as class
include get_template_directory().'/template-parts/options/options-layout.php'; 

if($display === 'card') :
  $offset = 'col-sm-12 offset-md-1 col-md-10';
  $col_class_img = 'col-sm-12 col-md-4';
  $col_class_content = 'col-sm-12 col-md-7';
else:
  $offset = 'col-sm-12 offset-md-1 col-md-10 offset-xl-2 col-xl-8';
  $col_class_img = 'col-sm-12 col-md-4';
  $col_class_content = 'col-sm-12 col-md-8';
endif;
?>

<section class="txtblock-img bg-light <?=$margin_class.' '.$padding_class.' '.$bg_class?>">
  <div class="container">
    <div class="row">
      <div class="<?=$offset?>">
        <?php if(have_rows('clone_txtblock_img')) : 
          while(have_rows('clone_txtblock_img')) : the_row(); ?>
            <?php // Layout Options Flex Class:
            // The variable $flex_direction sets a flex class of either .flex-row or .flex-row-reverse in /inc/options-block-layouts.php depending on what layout option is selected in the admin. ?>
            <div class="row <?=$display.' '.$flex_direction.' '.$padding_class?> align-items-center">
              <div class="<?=$col_class_img?> text-center">
                <?php // Image Block
                include get_template_directory().'/template-parts/layouts/parts/img-block.php'; ?>
              </div>
              <div class="<?=$col_class_content?>">
                <?php // Content Basic - section title, subtitle, content

                // heading options
                include get_template_directory() . '/template-parts/options/options-headings.php';

                // sub-heading options
                include get_template_directory() . '/template-parts/options/options-sub-headings.php';
                
                include get_template_directory().'/template-parts/layouts/parts/content-basic.php'; ?>
              </div>
            </div>
          <?php endwhile;
        endif; ?>  
      </div>
    </div>
  </div>
</section>
 
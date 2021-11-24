<?php // Text Block + Image (Offset Slider) - 
// Image block will become a slider if more than one image is added

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php';

// layout options: sets layout flex direction as class
include get_template_directory().'/template-parts/options/options-layout.php'; ?>

<section class="txtblock-img-offset <?=$margin_class.' '.$padding_class.' '.$bg_class?>">
  
  <?php if(have_rows('clone_txtblock_img')) : 
    while(have_rows('clone_txtblock_img')) : the_row(); ?>
      <?php // Layout Options Flex Class:
      // The variable $flex_direction sets a flex class of either .flex-row or .flex-row-reverse in /inc/options-block-layouts.php depending on what layout option is selected in the admin. ?>
      <div class="row <?=$flex_direction?> align-items-center">
        <div class="col-sm-12 col-md-6 text-center">          
          <?php 
          $images = get_sub_field('select_img');
          $count =  count($images);

          if($count === 1) {
            foreach( $images as $image ): ?>
              <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
            <?php endforeach;
          } else { ?>
            <div class="splide">
              <div class="splide__track">
                <ul class="splide__list">
                  <?php foreach( $images as $image ): ?>
                    <li class="splide__slide"><img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" /></li>
                  <?php endforeach;?>
                </ul>
              </div>
            </div>
          <?php }?>
          
        </div>
        <div class="col-sm-12 col-md-6">
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
</section>
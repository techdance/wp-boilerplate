<?php // Icon Row

global $btn_layout, $btn_row_class;

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// content alignment
include get_template_directory().'/template-parts/options/options-align-content.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php'; 

$images = get_sub_field('icon_blocks'); ?>

<section class="icon-row <?=$margin_class.' '.$padding_class.' '.$bg_class?>" style="<?=$style?>">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-center">
      <?php if( $images ):
        foreach( $images as $image ): ?>
          <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
        <?php endforeach;
      endif; ?>
    </div>
  </div>
</section>
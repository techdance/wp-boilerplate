<?php // Text Block + Background Image

global $btn_layout, $btn_row_class;

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-gradient.php'; 

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php';

// sub-heading options
include get_template_directory() . '/template-parts/options/options-sub-headings.php';

$bg_img = get_sub_field('bg_img');?>

<section class="txtblock-bg-img bg-img <?=$margin_class?>" style="background-image:url(<?=$bg_img['url']?>);">
  <div class="<?=$padding_class.' '.$gradient_direction.' '.$gradient_theme?>">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-lg-6">
          <?php // Content Basic - section title, subtitle, content
          include get_template_directory().'/template-parts/layouts/parts/content-basic.php'; ?>
          <?php // CTA Buttons - $btn_layout is dependent on text alignment which is set in theme options
          $btn_row_class = 'btn-row-inline';
          $btn_layout = 'justify-content-start';
          include get_stylesheet_directory().'/template-parts/layouts/parts/cta-btn-row.php';?>
        </div>
      </div>
    </div>
  </div>
</section>
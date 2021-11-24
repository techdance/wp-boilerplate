<?php // CTA Fullwidth

global $btn_layout, $btn_row_class;

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// content alignment
include get_template_directory().'/template-parts/options/options-align-content.php'; 

// display options (card or fullwidth)
include get_template_directory() . '/template-parts/options/options-display.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php'; 

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php';

// sub-heading options
include get_template_directory() . '/template-parts/options/options-sub-headings.php'; ?>

<section class="cta-full <?=$margin_class.' '.$padding_class.' '.$bg_class?>" style="<?=$style?>">
  <div class="container">
    <div class="row <?=$display.' '.$padding_class?>">
      <div class="col-sm-12 offset-md-1 col-md-10">
        <?php // Content Basic - section title, subtitle, content
        include get_template_directory().'/template-parts/layouts/parts/content-basic.php'; ?>
        <?php // CTA Buttons - $btn_layout is dependent on text alignment which is set in theme options
        $btn_row_class = 'btn-row-inline';
        include get_stylesheet_directory().'/template-parts/layouts/parts/cta-btn-row.php';?>
      </div>
    </div>
  </div>
</section>
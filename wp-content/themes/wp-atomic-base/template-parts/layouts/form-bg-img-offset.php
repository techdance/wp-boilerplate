<?php // Form (BG Image / Offset)

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php';

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php';

// sub-heading options
include get_template_directory() . '/template-parts/options/options-sub-headings.php';

// layout options: sets layout flex direction as class
include get_template_directory().'/template-parts/options/options-layout.php'; 

$formID = get_sub_field('formID'); ?>

<section class="form-bg-img-offset <?=$margin_class.' '.$padding_class.' '.$bg_class?>" style="background-image:url(<?=$bg_img['url']?>);">
      <?php // Layout Options Flex Class:
      // The variable $flex_direction sets a flex class of either .flex-row or .flex-row-reverse in /inc/options-block-layouts.php depending on what layout option is selected in the admin. ?>
      <div class="row <?=$flex_direction?> align-items-center">
        <div class="col-sm-12 col-md-6">
          <div class="section-intro">
            <?php // Content Basic - section title, subtitle, content
            include get_template_directory().'/template-parts/layouts/parts/content-basic.php'; ?>
          </div>
          <?=do_shortcode('[gravityform id="'.$formID.'" title="false" description="false" ajax="true"]')?>
        </div>
      </div>
</section>
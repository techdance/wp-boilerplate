<?php // CTA Callout

global $btn_layout, $btn_row_class;

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php';   

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php';

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php';?>

<section class="callout-cta <?=$margin_class.' '.$padding_class.' '.$bg_class?>" style="<?=$style?>">
  <div class="container">
    <div class="row align-items-center">
    <div class="col-sm-12 col-md-8">
      <?php // CTA Text
        $cta_txt = get_sub_field('cta_txt');
        echo $cta_txt ? '<'.$heading_option.'>'.$cta_txt.'</'.$heading_option.'>' : '';?>
      </div>
      <div class="col-sm-12 col-md-4">
        <?php // CTA Buttons - $btn_layout is dependent on text alignment which is set in theme options
        $btn_row_class = 'btn-row-stacked';
        $btn_layout = 'justify-content-center';
        include get_stylesheet_directory().'/template-parts/layouts/parts/cta-btn-row.php';?>
      </div>
    </div>
  </div>
</section>
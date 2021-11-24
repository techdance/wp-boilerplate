<?php // Callout - Heading / Text Block (2 Columns)

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php'; 

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php'; 

$section_title = get_sub_field('section_title');
$content = get_sub_field('content');?>

<section class="callout-txtblock-cols <?=$margin_class.' '.$padding_class.' '.$bg_class?>" style="<?=$style?>">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-4">
        <?=$section_title ? '<'.$heading_option.' class="section-title">'.$section_title.'</'.$heading_option.'>' : ''?>
      </div>
      <div class="col-sm-12 offset-md-1 col-md-7">
        <?=$content?>
      </div>
    </div>
  </div>
</section>
<?php // Icon + Text Block (Side by Side - 2 Columns)

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php';

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php';?>

<section class="txtblock-icon-beside <?=$margin_class.' '.$padding_class.' '.$bg_class?>">
  <div class="container col-blocks">
    <div class="row">
    <?php
    if(have_rows('txtblock_icon_blocks')) : 
      while(have_rows('txtblock_icon_blocks')) : the_row(); 
        $block_title = get_sub_field('block_title');
        $content = get_sub_field('content');
      ?>
        <div class="col-block col-sm-12 col-md-6">
          <div class="icon">
            <?php // Image Block
            include get_template_directory().'/template-parts/layouts/parts/img-block.php'; ?>
          </div>
          <div class="txtblock">
          <?=$block_title ? '<'.$heading_option.' class="mb-5">'.$block_title.'</'.$heading_option.'>' : '';?>
          <?=$content ? $content : '';?>
          </div>
        </div>
      <?php endwhile;
    endif; ?>  
    </div>
  </div>
</section>
 
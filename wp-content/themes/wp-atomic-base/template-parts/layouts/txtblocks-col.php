<?php // Icon + Text Block Columns

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php';?>

<section class="txtblocks-col <?=$margin_class?>">
  <div class="container">
    <div class="row">
    <?php
    if(have_rows('txtblocks_col')) : 
      while(have_rows('txtblocks_col')) : the_row(); 
        $block_title = get_sub_field('block_title');
        $content = get_sub_field('content');
      ?>
        <div class="col-sm-12 col-md-6">
          <?=$block_title ? '<'.$heading_option.'>'.$block_title.'</'.$heading_option.'>' : '';?>
          <?=$content ? $content : '';?>
        </div>
      <?php endwhile;
    endif; ?>  
    </div>
  </div>
</section>
 
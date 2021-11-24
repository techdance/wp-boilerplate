<?php // Icon + Text Block (Stacked - Dynamic Columns)
// ACF Repeater: 2 min - 4 max

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php';

$blocks = get_sub_field('txtblock_icon_blocks');
$count = count($blocks);

if($count === 4) :
  $row_class = 'row';
  $col_class = 'col-sm-12 col-md-3';
elseif($count === 3) :
  $row_class = 'row';
  $col_class = 'col-sm-12 col-md-4';
elseif($count === 2) :
  $row_class = 'row col-sm-12 offset-md-1 col-md-10';
  $col_class = 'col-sm-12 col-md-6';
endif; ?>

<section class="txtblock-img-full <?=$margin_class?>">
  <div class="container col-blocks">
    <div class="<?=$row_class?>">
    <?php
    if(have_rows('txtblock_icon_blocks')) : 
      while(have_rows('txtblock_icon_blocks')) : the_row(); 
        $block_title = get_sub_field('block_title');
        $content = get_sub_field('content');
      ?>
        <div class="col-block <?=$col_class?>">
          <div class="icon text-center mb-5">
            <?php // Image Block
            include get_template_directory().'/template-parts/layouts/parts/img-block.php'; ?>
          </div>
          <?=$block_title ? '<'.$heading_option.' class="mb-5">'.$block_title.'</'.$heading_option.'>' : '';?>
          <?=$content ? $content : '';?>
        </div>
      <?php endwhile;
    endif; ?>  
    </div>
  </div>
</section>
 
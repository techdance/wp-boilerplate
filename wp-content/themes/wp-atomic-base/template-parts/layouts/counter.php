<?php // Testimonail Blocks
// ACF Relationship: 1 min - 3 max

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

$counter_blocks = get_sub_field('counter_blocks');
$count = count($counter_blocks);

if($count === 2) :
  $row_class = 'row col-sm-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6';
  $col_class = 'col-sm-12 col-md-6';
elseif($count === 3) :
  $row_class = 'row';
  $col_class = 'col-sm-12 col-md-4';
elseif($count === 4) :
  $row_class = 'row col-sm-12 offset-md-2 col-md-8 offset-lg-0 col-lg-12';
  $col_class = 'col-sm-12 col-md-6 col-lg-3';
endif; ?>

<section class="counter <?=$margin_class?>">
  <div class="container">
    <div class="<?=$row_class?> p-0">
      <?php if( have_rows('counter_blocks') ): 
        while ( have_rows('counter_blocks') ) : the_row(); 
          $starting_num = get_sub_field('starting_number');
          $ending_num = get_sub_field('ending_number');
          $label = get_sub_field('label');
        ?>
        <div class="<?=$col_class?> text-center mb-5">
          <div class="num"><?=$ending_num?></div>
          <div class="label"><?=$label?></div>
        </div>
        <?php endwhile;
      endif; ?>
    </div>
  </div>
</section>
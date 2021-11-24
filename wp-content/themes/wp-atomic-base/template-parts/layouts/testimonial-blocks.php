<?php // Testimonail Blocks
// ACF Relationship: 1 min - 3 max

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

$testimonials = get_sub_field('select_testimonials');
$count = count($testimonials);

if($count === 2) :
  $row_class = 'row col-sm-12 offset-md-1 col-md-10';
  $col_class = 'col-sm-12 col-md-6';
elseif($count === 3) :
  $row_class = 'row';
  $col_class = 'col-sm-12 col-md-4';
else: 
  $row_class = 'row col-sm-12 offset-md-2 col-md-8';
  $col_class = 'col-sm-12';
endif; ?>

<section class="testimonial-blocks <?=$margin_class?>">
  <div class="container">
    <div class="<?=$row_class?>">
      <?php foreach( $testimonials as $post ): 
        $author = get_field('author');
        $details = get_field('details');
        setup_postdata($post); ?>
        
        <div class="<?=$col_class?>">
          <?php // Testimonial Content
          include get_stylesheet_directory().'/template-parts/layouts/parts/testimonial.php';?>
        </div>
      
      <?php endforeach;
      wp_reset_postdata(); ?>
    </div>
  </div>
</section>
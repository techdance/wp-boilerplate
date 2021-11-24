<?php // Testimonial Slider
// Testimonial block will become a slider if more than testimonial is selected

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php'; 

$testimonials = get_sub_field('select_testimonials');
$count = count($testimonials);?>

<section class="testimonial-slider <?=$margin_class.' '.$padding_class.' '.$bg_class?>" style="<?=$style?>">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 offset-md-1 col-md-10 offset-lg-2 col-lg-8">
        <?php foreach( $testimonials as $post ): 
          $author = get_field('author');
          $details = get_field('details');
          setup_postdata($post); 

          if($count === 1) { ?>
            <?php // Testimonial Content
            include get_stylesheet_directory().'/template-parts/layouts/parts/testimonial.php';?>
          <?php } else { ?>
            
            <div class="splide">
              <div class="splide__track">
                <ul class="splide__list">
                  <li class="splide__slide">
                    <?php // Testimonial Content
                    include get_stylesheet_directory().'/template-parts/layouts/parts/testimonial.php';?>
                  </li>
                </ul>
              </div>
            </div>
          <?php }?>
        
        <?php endforeach;
        wp_reset_postdata(); ?>
      </div>
    </div>
  </div>
</section>
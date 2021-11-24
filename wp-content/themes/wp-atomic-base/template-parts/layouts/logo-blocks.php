<?php // Logo Blocks

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

$images = get_sub_field('logo_blocks'); ?>

<section class="logo-blocks <?=$margin_class?>">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-center">
      <?php if( $images ):
        foreach( $images as $image ): ?>
          <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
        <?php endforeach;
      endif; ?>
    </div>
  </div>
</section>
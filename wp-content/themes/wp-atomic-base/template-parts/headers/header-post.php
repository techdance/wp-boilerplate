<?php // Header - Blog ?>

<header id="hero" class="bg-img margin-lg section-py-lg" style="background-image:url(<?=get_the_post_thumbnail_url()?>);">
  <div class="container">
    <h1><?=the_title()?></h1>
    <?=the_excerpt()?>
    <?php $categories = get_the_category();
    if($categories) : ?>
      <ul class="cat-links d-flex">
        <?php foreach($categories as $category):
          echo '<li>'.$category->name.'</li>';
        endforeach; ?>
      </ul>
    <?php endif; ?>
    <div class="date">
      <?php the_time(get_option('date_format')); ?>
    </div>
    <div class="social social-share">
      <?=do_shortcode( '[Sassy_Social_Share]' )?>
    </div>
  </div>
</header>
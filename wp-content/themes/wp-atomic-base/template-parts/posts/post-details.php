<?php // Post Details ?>

<div class="post row">
  <?php // post thumbnail
  if($show_thumb) { ?>
    <div class="post-thumb d-none d-md-block <?=$thumb_col_class?>">
      <?=the_post_thumbnail()?>
    </div>
  <?php } ?>

  <div class="post-details <?=$details_col_class?>">
    <h2><a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>

    <?php // post excerpt
    if($show_excerpt) { ?>
      <div class="excerpt"><?=the_excerpt()?></div>
    <?php } ?>
  
    <?php $categories = get_the_category();
    if($categories) : ?>
      <ul class="cat-links d-flex">
        <?php foreach($categories as $category):
          $catURL = esc_url( get_category_link( $category->term_id ) );
          echo '<li><a href="'.$catURL.'">'.$category->name.'</a></li>';
        endforeach; ?>
      </ul>
    <?php endif; ?>

    <div class="date">
      <?php the_time(get_option('date_format')); ?>
    </div>

  </div>
</div>
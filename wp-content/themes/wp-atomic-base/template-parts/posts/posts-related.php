<?php // Related Posts ?>

<section class="posts-related">
  <div class="container">

    <h3>Related posts</h3>
    <div class="row">
      <?php
      $orig_post = $post;
      global $post;
      $tags = wp_get_post_tags($post->ID);
        
      if ($tags) :
        $tag_ids = array();
        foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
          $args=array(
          'tag__in' => $tag_ids,
          'post__not_in' => array($post->ID),
          'posts_per_page'=>3, // Number of related posts to display.
          'caller_get_posts'=>1
          );
        
          $my_query = new wp_query( $args );
        
            while( $my_query->have_posts() ) {
              $my_query->the_post();
            ?>
              <div class="col-sm-12 col-md-4">
                <?php // Post Thumb & Post Details
                  $show_thumb = true;
                  $show_excerpt = true;
                  $thumb_col_class = 'col-sm-12';
                  $details_col_class = 'col-sm-12';
                  include get_stylesheet_directory().('/template-parts/posts/post-details.php'); ?>
              </div>        
            <? }
        endif;
      $post = $orig_post;
      wp_reset_query(); ?>
    </div>
  </div>
</section>
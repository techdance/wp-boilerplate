<?php // Search Widget ?>

<div class="search">
  <?php if ( is_active_sidebar( 'search' ) ) : ?>
    <div id="secondary" class="widget-area" role="complementary">
      <?php dynamic_sidebar( 'search' ); ?>
    </div>
  <?php endif; ?>
</div>
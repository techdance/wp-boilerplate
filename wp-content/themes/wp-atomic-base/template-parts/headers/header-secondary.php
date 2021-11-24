<?php // Header - Secondary ?>
		
<?php if( have_rows('header_secondary') ):
  while ( have_rows('header_secondary') ) : the_row(); 
    $headline = get_sub_field('headline');
    $subtitle = get_sub_field('subtitle');
  ?>
    <header class="bg-img" <?= $bg_img ? 'style="background-image:url('.$bg_img['url'].');"' : '' ?>>
      <div class="container">
        <h1><?=$headline?></h1>
        <h2><?=$subtitle?></h2>
      </div>
    </header>
  <?php endwhile;
endif; ?>
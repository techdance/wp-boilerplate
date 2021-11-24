<?php // Header - Service Offering ?>
		
<?php if( have_rows('header_service') ):
  while ( have_rows('header_service') ) : the_row(); 
    $headline = get_sub_field('headline');
    $subtitle = get_sub_field('subtitle');
    $bg_img = get_sub_field('bg_img');
  ?>
    <header class="bg-img" <?= $bg_img ? 'style="background-image:url('.$bg_img['url'].');"' : '' ?>>
      <div class="container text-center">
        <h1><?=$headline?></h1>
        <h2><?=$subtitle?></h2>
      </div>
    </header>
  <?php endwhile;
endif; ?>
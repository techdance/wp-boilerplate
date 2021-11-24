<?php // Header - Primary 
global $btn_layout;
?>
		
<?php if( have_rows('header_primary') ):
  while ( have_rows('header_primary') ) : the_row(); 
    $headline = get_sub_field('headline');
    $subtitle = get_sub_field('subtitle');
    $bg_img = get_sub_field('bg_img');
  ?>
    <header class="bg-img" <?= $bg_img ? 'style="background-image:url('.$bg_img['url'].');"' : '' ?>>
      <div class="container text-center">
        <h1><?=$headline?></h1>
        <h2><?=$subtitle?></h2>
        <?php // CTA Buttons
        $btn_layout = 'justify-content-center';
        include get_stylesheet_directory().'/template-parts/layouts/parts/cta-btn-row.php';?>
      </div>
    </header>
  <?php endwhile;
endif; ?>
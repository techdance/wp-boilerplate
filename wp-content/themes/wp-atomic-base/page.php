<?php 
/**
 * default for pages
 * @link https://developer.wordpress.org/themes/basics/template-files/
 */
?>

<?php get_header(); ?>

	<div class="page">
    <?= // Header Secondary
      get_template_part( 'template-parts/headers/header-secondary' );
      get_template_part( 'template-parts/layouts/_layouts' );
    ?>
	</div>

<?php get_footer(); ?>
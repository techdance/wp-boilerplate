<?php
/**
 * single
 * @link https://developer.wordpress.org/themes/basics/template-files/
 */
?>

<?php get_header(); ?>

<?php if ( have_posts() ): ?>
	<?php while ( have_posts() ) : the_post(); ?>

	<div class="page">
    <?= // Header Secondary
      get_template_part( 'template-parts/headers/header-post' );
      get_template_part( 'template-parts/layouts/_layouts' );
      get_template_part( 'template-parts/posts/posts-related' );

    ?>
	</div>
		
	<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
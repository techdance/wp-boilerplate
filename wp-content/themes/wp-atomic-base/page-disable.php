<?php // Template Name: Disable Gutenburg ?>
<?php get_header(); ?>

<?php if ( have_posts() ): ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<div class="page">
			<?php get_template_part( 'template-parts/components/headers'); ?>
			
			<section role="main">
				<?php include'template-parts/_layouts.php'; ?>		
			</section>
		</div>

	<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
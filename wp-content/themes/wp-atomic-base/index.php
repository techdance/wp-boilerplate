<?php
/**
 * the fallback for all!!!
 * @link https://developer.wordpress.org/themes/basics/template-files/
 */
?>
<?php get_header(); ?>

<section role="main">
		<div class="container">
			<?php if ( have_posts() ): 
				while ( have_posts() ) : the_post(); ?>
				<?php // Post Thumb & Post Details
					$show_thumb = true;
					$show_excerpt = true;
					$thumb_col_class = 'col-sm-12 col-md-3';
					$details_col_class = 'col-sm-12 col-md-8';
					include get_stylesheet_directory().('/template-parts/posts/post-details.php'); ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
</section>

<?php get_footer(); ?>
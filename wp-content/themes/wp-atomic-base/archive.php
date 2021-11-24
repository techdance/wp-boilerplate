<?php
/**
 * archive
 * @link https://developer.wordpress.org/themes/basics/template-files/
 */
?>
<?php get_header(); ?>

<?php if ( have_posts() ): ?>

	<div class="page">
		<?= // Header - Category
		get_template_part( 'template-parts/headers/header-cat' )?>
		<?php while ( have_posts() ) : the_post(); ?>
			<section role="main">
				<div class="container">
					<article>
						<h2><a href="<?php esc_url( the_permalink() ); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
						<time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate><?php the_date(); ?> <?php the_time(); ?></time> <?php comments_popup_link('Leave a Comment', '1 Comment', '% Comments'); ?>
						<?php the_content(); ?>
					</article>
				</div>
			</section>
		
		<?php endwhile; ?>
		
		<section>
			<div class="container">			
				<div class="pagination">
					<?php
						$image_path = get_template_directory_uri();
						
						the_posts_pagination( array(
							'mid_size'  => 2,
							'prev_text' => __( 'Back', 'textdomain' ),
							'next_text' => __( 'Onward', 'textdomain' ),
						) );
					?>		
				</div>
			</div>
		</section>
		
	</div>
	
<?php endif; ?>

<?php get_footer(); ?>
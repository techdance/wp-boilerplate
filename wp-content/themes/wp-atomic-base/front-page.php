<?php // Template Name: Homepage ?>
<?php get_header(); ?>

	<div class="page">
        <?php 
            get_template_part( 'template-parts/headers/header-primary' );
            get_template_part( 'template-parts/modules/filterbar' )
        ?>
	</div>

<?php get_footer(); ?>
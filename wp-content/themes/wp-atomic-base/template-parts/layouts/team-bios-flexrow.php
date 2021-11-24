<?php // Team Bios
// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

$term = get_sub_field('select_category');

$args = array(
  'post_type' => 'team',
  'meta_key' => 'first_name', // name of custom field
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'tax_query' => array(
    array(
      'taxonomy' => 'member_category',
      'field' => 'term_id',
      'terms' => $term,
    )
  )
);
$query = new WP_Query( $args ); ?>

<section class="team-bios <?=$margin_class?>">
  <div class="container">
    <div class="row">
      <?php while ( $query->have_posts() ):
        $query->the_post();
        $thumb = get_the_post_thumbnail();
        $firstname = get_field('first_name');
        $lastname = get_field('last_name');
        $title = get_field('title');
      ?>
        <div class="col-sm-12 col-md-4 col-lg-3">
          <?=$thumb?>
          <div class="details">
            <div class="name"><?=$firstname.' '.$lastname?></div>
            <div class="title"><?=$title?></div>
          </div>
          <a href="javascript:void(0)" class="readmore">Read Bio</a>
        </div>
      <?php endwhile;
      wp_reset_query();?>
    </div>
  </div>
</section>
<?php // Content Fullwidth

global $btn_layout, $btn_row_class;

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';?>

<section class="content-full <?=$margin_class?>">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 offset-md-1 col-md-10 col-lg-8">
        <article>
          <?=get_sub_field('content')?>
        </article>
      </div>
    </div>
  </div>
</section>
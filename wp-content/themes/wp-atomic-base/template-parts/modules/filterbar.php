<?php // Filter Bar

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php'; 

$section_title = get_sub_field('section_title'); ?>

<section class="filterbar section-py-md bg-dark">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-4 text-white">
        <h3>Module: Filterbar</h3>
      </div>
      <div class="col-sm-12 offset-md-1 col-md-7">

        <?php // Taxonomy
        $taxonomyone = get_categories( array(
        'taxonomy' => 'taxonomyone',
        'orderby' => 'name',
        'order'   => 'ASC'
        ) );

        $taxonomytwo = get_categories( array(
        'taxonomy' => 'taxonomytwo',
        'orderby' => 'name',
        'order'   => 'ASC'
        ) );
        ?>

        <form class="controls">
            <div class="select-row">
            <fieldset data-filter-group class="select-wrapper">
                <select>
                    <option value=""  data-filter="all">Taxonomy One</option>
                    <?php // Taxonomy One
                    foreach( $taxonomyone as $tax ) {
                        echo '<option value="'.$tax->slug.'">'.$tax->name.'</option>';
                    } ?>    
                </select>
                <select>
                    <option value=""  data-filter="all">Taxonomy Two</option>
                    <?php // Taxonomy Two
                    foreach( $taxonomytwo as $tax ) {
                        echo '<option value="'.$tax->slug.'">'.$tax->name.'</option>';
                    } ?>    
                </select>
            </fieldset>
            </div>
            <div class="search mt-5"><button type="search" class="btn btn-secondary">Search</button></div>
        </form>
      </div>
    </div>
  </div>
</section>
<?php // Accordion

// margin options
include get_template_directory().'/template-parts/options/options-margins.php'; 

$row_index = get_row_index(); ?>

<section class="accordion <?=$margin_class?>">
  <div class="container">
    <?php if( have_rows('clone_accordion') ):  $row_counter = 1; ?>

      <?php // Get the row index to add unique id to accommodate multiple accordions on page ?> 
      <div id="accordion_<?=$row_index?>">
        <?php while ( have_rows('clone_accordion') ) : the_row(); 
          $row_title = get_sub_field('row_title');
          $content = get_sub_field('content');
        ?>
          <?php // Accordion Heading
          // Using $row_index and $row_counter to populate unique id on accordion rows, data-targets, collapse ID, aria-labelledby ?>

          <div class="accordion-row">
            <div class="heading" id="heading_<?=$row_index.'_'.$row_counter; ?>">
                <p class="m-0">
                  <a href="javascript:void(0);" class="collapsed" data-toggle="collapse" data-target="#collapse_<?=$row_index.'_'.$row_counter;; ?>" aria-expanded="false" aria-controls="collapseTwo">
                    <img class="expanded" src="<?=get_template_directory_uri()?>/src/images/arrow-up.svg" alt="Read more about <?=$row_title?>" />
                    <img class="collapsed" src="<?=get_template_directory_uri()?>/src/images/arrow-down.svg" alt="Read more about <?=$row_title?>" />
                    <?=$row_title; ?>
                  </a>
                </p>
            </div>
          </div>

          <?php // Accordion Row
          // Using $row_index and $row_counter to populate unique id on accordion rows, data-targets, collapse ID, aria-labelledby ?>

          <div id="collapse_<?php echo $row_index.'_'.$row_counter; ?>" class="collapse" aria-labelledby="heading_<?php echo $row_index.'_'.$row_counter;; ?>" data-parent="#accordion_<?php echo $row_index; ?>">
            <article>
              <?=$content?>
            </article>
          </div>

        <?php $row_counter++; endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
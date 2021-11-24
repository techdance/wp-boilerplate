<?php // Document Downloads

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php'; 

$section_title = get_sub_field('section_title');
$content = get_sub_field('content');?>

<section class="doc-downloads <?=$margin_class?>">
  <div class="container">
    <div class="section-intro">
      <?=$section_title ? '<'.$heading_option.' class="section-title">'.$section_title.'</'.$heading_option.'>' : ''?>
      <?=$content?>
    </div>

    <div class="doc-list">
      <ul class="row">
        <?php if( have_rows('doc_dl') ): 
          while ( have_rows('doc_dl') ) : the_row(); 
            $file = get_sub_field('file');
            $file_title = $file['title'];
            $fileURL = $file['url'];
          ?>

            <li class="col-sm-12">
              <a href="<?=$fileURL?>" class="d-flex border-bottom px-3 py-4" target="_blank">
                <div class="d-flex align-items-start">
                  <img class="mr-3" src="" alt="Download <?=$file_title?>" width="" height="" />
                  <p class="title mr-4"><?=$file_title?></p>
                </div>
                <div class="dl-info">
                  <img class="mr-3" src="" alt="Download <?=$file_title?>" width="" height="" />
                  <p class="readmore">Download</p>
                </div>
                
              </a>
            </li>

          <?php endwhile;
        endif; ?>
      </ul>
    </div>
  </div>
</section>
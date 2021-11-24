<?php // Video Featured - has container options - used in main content templates

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// padding options
include get_template_directory().'/template-parts/options/options-padding.php'; 

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php';

// container options
include get_template_directory().'/template-parts/options/options-container.php'; 

if($container === 'container') :
  $bg_class = $bg_class;
else:
  //$bg_class = 'no-bgcolor';
  echo $bg_class;
endif; ?>

<section class="video-contained <?=$margin_class.' '.$padding_class.' '.$bg_class?>">
  <div class="<?=$container?>">
    <div class="row margin-md">
      <div class="col-sm-12 offset-md-1 col-md-10">
        <?php // Content Basic - section title, subtitle, content
        $align_content = 'text-center';
        include get_template_directory().'/template-parts/layouts/parts/content-basic.php'; ?>
      </div>
    </div>
    <?php // Video Responsive
    include get_template_directory().'/template-parts/layouts/parts/video-responsive.php'; ?>
  </div>
</section>
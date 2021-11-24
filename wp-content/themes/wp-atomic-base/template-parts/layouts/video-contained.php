<?php // Video Contained (Blog) - no container options - will always be contained - used in blog templates

// margin options
include get_template_directory().'/template-parts/options/options-margins.php';

// heading options
include get_template_directory() . '/template-parts/options/options-headings.php';

$video_title = get_sub_field('video_title'); 
$caption = get_sub_field('caption'); ?>

<section class="video-contained <?=$margin_class.' '.$padding_class.' '.$bg_class?>">
  <div class="container">
    <?php 
      echo $video_title ? '<'.$heading_option.'>'.$video_title.'</'.$heading_option.'>' : '';
      // Video Responsive
      include get_template_directory().'/template-parts/layouts/parts/video-responsive.php'; 
      echo $caption ? '<div class="caption mt-5"><p>'.$caption.'</p></div>' : '';
    ?>
  </div>
</section>
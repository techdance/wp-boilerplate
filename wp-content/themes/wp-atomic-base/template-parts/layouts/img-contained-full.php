<?php // Image (Fullwidth) 

// margin options
include get_template_directory().'/template-parts/options/options-margins.php'; 

// padding options
include get_template_directory().'/template-parts/options/options-padding.php';  

// background color options
include get_template_directory() . '/template-parts/options/options-bg-type.php'; 

// container options
include get_template_directory().'/template-parts/options/options-container.php'; 

$img_full = get_sub_field('img_full'); 

if($container === 'container') :
  $bg_class = $bg_class;
else:
  $bg_class = 'no-bgcolor';
endif; ?>

<section class="img-contained-full <?=$margin_class.' '.$padding_class.' '.$bg_class?>">
  <div class="<?=$container?>">
    <img src="<?=$img_full['url']?>" alt="<?=$img_full['alt']?>" />
    
    <?php if($container === 'container') : ?>
      <?=$img_full['caption'] ? '<div class="caption mt-5"><p>'.$img_full['caption'].'</p></div>' : '' ?>
    <?php endif; ?>
    
  </div>
</section>
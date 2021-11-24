<?php // Content Basic 

$section_title = get_sub_field('section_title');
$subtitle = get_sub_field('subtitle');
$content = get_sub_field('content'); ?>

<article class="<?=$align_content?>">
  <?=$section_title ? '<'.$heading_option.' class="section-title">'.$section_title.'</'.$heading_option.'>' : ''?>
  <?=$subtitle ? '<'.$subheading_option.' class="subtitle">'.$subtitle.'</'.$subheading_option.'>' : ''?>
  <?=$content ? $content : ''?>
</article>
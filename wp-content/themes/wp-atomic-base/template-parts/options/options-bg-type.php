<?php // Background color options
global $bgcolor, $bgcolor;

// Background Type - Image or Solid
$bg_type = get_sub_field_object('bg_type');
$bg_type = $bg_type['value'];

// Background Color
$bgcolor = get_sub_field_object('select_bg_color');
$bgcolor = $bgcolor['value'];

// Background Image
$bg_img = get_sub_field('bg_img'); 

// Background Styles
if($bg_type === 'bg-img') :
  $bg_class = $bg_type;
  $style = 'background-image:url('.$bg_img['url'].');';
else:
  $bg_class = $bg_type.' '.$bgcolor;
  $style = 'background-image:none;';
endif;
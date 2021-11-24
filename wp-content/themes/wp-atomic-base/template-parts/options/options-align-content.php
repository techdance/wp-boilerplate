<?php // Alignment Options

$align_content = get_sub_field_object('align_content');
$align_content = $align_content['value'];

if($align_content === 'text-center') :
  $btn_layout = 'justify-content-center';
elseif($align_content === 'text-left') :
  $btn_layout = 'justify-content-start';
endif;
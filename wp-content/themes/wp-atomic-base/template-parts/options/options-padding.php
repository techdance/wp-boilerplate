<?php // Clone: Padding Options
//       - None
//       - Padding SM
//       - Padding MD
//       - Padding LG

global $padding_class;
$clone_padding_options = get_sub_field_object('clone_padding_options');
$padding_class = $clone_padding_options['value'];

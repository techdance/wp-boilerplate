<?php // Clone: Margin Options
//       - None
//       - Margin SM
//       - Margin MD
//       - Margin LG

global $margin_class;
$clone_margin_options = get_sub_field_object('clone_margin_options');
$margin_class = $clone_margin_options['value'];

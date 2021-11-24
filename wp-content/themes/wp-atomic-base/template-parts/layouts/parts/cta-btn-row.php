<?php // Clone: CTA Buttons (Primary/Secondary) - button class added dynamically 

// - first button gets class .btn-primary auto-magically
// - second button gets class .btn-secondary auto-magically
      
// $btn_layout is set in template-parts/parts/options/options-theme.php to justify buttons in accordance to content alignment selection
// $btn_row_class is set in main component to declare whether buttons are inline or stacked

global $btn_layout, $btn_row_class;

// Primary & Secondary CTA Buttons
if( have_rows('clone_cta_btns') ):
  echo '<div class="btn-row '.$btn_layout.' '.$btn_row_class.' mt-5">';
    $i = 0; while ( have_rows('clone_cta_btns') ) : the_row(); 
      $btn_txt = get_sub_field('btn_txt');
      $pagelink = get_sub_field('pagelink');

      if( $i % 2 == 0 ) :
        $btn_class = 'btn-primary';
      else :
        $btn_class = 'btn-secondary'; 
      endif; 
    ?>
        <?php if($btn_txt) : ?>
          <div><a class="btn <?=$btn_class?>" href="<?=$pagelink['url']?>" target="<?=$pagelink['target']?>"><?=$btn_txt?></a></div>
        <?php endif;?>
    <?php $i++; endwhile;
  echo '</div>';

// Single CTA Button
elseif( have_rows('clone_cta_btn_single') ):
  echo '<div class="btn-row '.$btn_layout.' mt-5">';
    while ( have_rows('clone_cta_btn_single') ) : the_row(); 
      $btn_txt = get_sub_field('btn_txt');
      $pagelink = get_sub_field('pagelink');
      if($btn_txt) : ?>
        <div><a class="btn btn-secondary" href="<?=$pagelink['url']?>" target="<?=$pagelink['target']?>"><?=$btn_txt?></a></div>
      <?php endif;
    endwhile;
  echo '</div>';
endif;
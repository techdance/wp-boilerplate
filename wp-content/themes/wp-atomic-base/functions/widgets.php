<?php
/**
 * Register widgetized areas.
 *
 */


function wpb_widgets_init() {
 
  register_sidebar( array(
      'name' => __( 'Main Sidebar', 'wpb' ),
      'id' => 'sidebar-1',
      'description' => __( 'The main sidebar appears on the right on each page except the front page template', 'wpb' ),
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
  ) );

  register_sidebar( array(
      'name' =>__( 'Search', 'wpb'),
      'id' => 'search',
      'description' => __( 'Search element', 'wpb' ),
      'before_widget' => '<div>',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
  ) );
  }

add_action( 'widgets_init', 'wpb_widgets_init' );

?>
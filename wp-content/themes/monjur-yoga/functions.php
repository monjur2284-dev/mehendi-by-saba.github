
<?php
// Theme setup
add_action('after_setup_theme', function(){
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('woocommerce');
});

// Enqueue
add_action('wp_enqueue_scripts', function(){
  wp_enqueue_style('monjur-yoga-style', get_stylesheet_uri(), [], '1.0.0');
  wp_enqueue_script('monjur-yoga-front', get_template_directory_uri() . '/assets/front.js', ['jquery'], '1.0.0', true);
});

// Register menu
add_action('init', function(){
  register_nav_menu('primary','Primary Menu');
});

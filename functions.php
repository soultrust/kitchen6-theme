<?php

function k6_files() {
  wp_enqueue_script('main-k6-js', get_theme_file_uri('/build/index.js'));
  wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap');
  wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
  wp_enqueue_style('k6_main_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('k6_extra_styles', get_theme_file_uri('/build/index.css'));
}
add_action('wp_enqueue_scripts', 'k6_files');

function k6_features() {
  add_theme_support('title-tag');
}
add_action('after_setup_theme', 'k6_features');

function post_remove () { 
  remove_menu_page('edit.php');
} 
add_action('admin_menu', 'post_remove');
?>
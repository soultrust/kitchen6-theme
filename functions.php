<?php

function k6_files() {
  wp_enqueue_script('main-js', get_theme_file_uri('/build/index.js'));
  wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap');
  wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
  wp_enqueue_style('k6_main_styles', get_theme_file_uri('/style.css'));
  wp_enqueue_style('k6_extra_styles', get_theme_file_uri('/build/style-index.css'));

  // To change root url on production / local
  // wp_localize_script(
  //   'main-js', 'k6', array(
  //     'root_url' => get_site_url()
  //   )
  // );
}
add_action('wp_enqueue_scripts', 'k6_files');

function k6_post_types() {
  register_post_type('recipe', array(
    'supports' => array('thumbnail'),
    'show_in_rest' => true,
    'rewrite' => array('slug' => 'recipes'),
    'has_archive' => true,
    'menu_position' => 2,
    'public' => true,
    'labels' => array(
      'name' => 'Recipes',
      'all_items' => 'All Recipes',
      'add_new_item' => 'Add New Recipe',
      'edit_item' => 'Edit Recipe',
      'singular_name' => 'Recipe'
    ),
    'menu_icon' => 'dashicons-food'
  ));
}
add_action('init', 'k6_post_types');

function k6_features() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'k6_features');

// Remove "Posts" from admin menu
function post_remove () { 
  remove_menu_page('edit.php');
} 
add_action('admin_menu', 'post_remove');

// Make admin use main style from front end
function ourLoginCSS() {
  wp_enqueue_style( 'main-style', get_stylesheet_directory_uri() . '/style.css' );
  wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet');
}
add_action('login_enqueue_scripts', 'ourLoginCSS');

// The following is necessary for enabling usage of js modules
function add_type_attribute($tag, $handle, $src) {
  // if not your script, do nothing and return original $tag
  if ( 'main-js' !== $handle ) {
      return $tag;
  }
  // change the script tag by adding type="module" and return it.
  $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
  return $tag;
}
add_filter('script_loader_tag', 'add_type_attribute' , 10, 3);




















// add_filter( 'enter_title_here', 'custom_enter_title_text' );

// function custom_enter_title_text( $input ) {
//   if ( 'recipe' === get_post_type() ) {
//       return __( 'Add recipe title', 'wp-rig' );
//   }
//   return $input;
// }


// function change_columns( $cols ) {
//   $cols = array(
//     'title'      => 'Recipe Title'
//   );
//   return $cols;
// }

// add_action("manage_site_posts_custom_column", "custom_columns", 10, 2);
// add_filter("manage_recipe_posts_columns", "change_columns");

/**
 * Adds custom classes to indicate whether a sidebar is present to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array Filtered body classes.
 */

// add_filter('body_class', 'filter_body_classes');

// function filter_body_classes( array $classes ) : array {
//     if (is_active_sidebar('sidebar-1')) {
//         global $template;
//         if (! in_array(
//             basename($template),
//             array('single-recipe.php', '404.php', '500.php', 'offline.php')
//         )
//         ) {
//             $classes[] = 'has-sidebar';
//         }
//     }
//     return $classes;
// }

// add_action('admin_enqueue_scripts', 'load_admin_style');

// function load_admin_style()
// {
//     wp_enqueue_style('admin_css', get_stylesheet_directory_uri() . '/admin-style.css', false, '1.0.0');
// }


// add_action('wp_enqueue_scripts', 'load_dashicons_front_end');

// function load_dashicons_front_end()
// {
//     wp_enqueue_style('dashicons');
// }


// Redirect subscriber-level members to home page.
// (demo account is a subcriber)
// function redirectToFrontEnd() {
//   $ourCurrentUser = wp_get_current_user();

//   if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
//     wp_redirect(site_url('/'));
//     exit;
//   }
// }
// add_action('admin_init', 'redirectToFrontEnd');

// Redirect everybody to home page after logging in.
// Subsequent visits to admin will not redirect.
// add_filter('login_redirect', 'mylogin_redirect', 10, 3);

// function mylogin_redirect($redirect_to, $url_redirect_to, $user) {
//   return home_url();
// }

// Remove admin bar for subscribers
// add_action('wp_loaded', 'noSubsAdminBar');

// function noSubsAdminBar() {
//   $ourCurrentUser = wp_get_current_user();

//   if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
//     show_admin_bar(false);
//   }
// }

// // Customize Title Link for Login Screen
// add_filter('login_headerurl', 'ourHeaderUrl');

// function ourHeaderUrl() {
//   return esc_url(site_url('/'));
// }

// // Change the Login Page Title
// add_filter('login_headertitle', 'ourLoginTitle');

// function ourLoginTitle() {
//   return get_bloginfo('name');
// }

// add_action('rest_api_init', 'k6RegisterSearch');

// function k6RegisterSearch() {
//   register_rest_route('k6/v1', 'search', array(
//     'methods' => WP_REST_SERVER::READABLE,
//     'callback' => 'k6SearchResults'
//   ));
// }

// function k6SearchResults($data) {
//   $recipes = new WP_Query(array(
//     'post_type' => 'recipe',
//     's' => sanitize_text_field($data['term'])
//   ));
//   wp_reset_postdata();

//   $results = array(
//     'recipes' => array(),
//     'tags' => array(),
//     'taggedRecipes' => array(),
//     'term' => ''
//   );

  // while($recipes->have_posts()) {
  //   $recipes->the_post();

  //   if (get_post_type() === 'recipe') {
  //     array_push($results['recipes'], array(
  //       'title' => get_the_title(),
  //       'permalink' => get_the_permalink()
  //     ));
  //   }
  // }

  // $tags = get_tags(array(
  //   'search' => sanitize_text_field($data['term'])
  // ));

  // GET tags (tag archive pages) that have search term in the tag name
  // foreach ($tags as &$tag) {
  //   array_push($results['tags'], array(
  //     'post_type' => 'tag',
  //     'name' => $tag->name,
  //     'permalink' => get_tag_link($tag->term_id)
  //   ));
  // }

  // $taggedRecipes = new WP_Query(array(
  //   'post_type' => 'recipe',
  //   'tag' => sanitize_text_field($data['term'])
  // ));

  // GET recipes that have a tag with a title that matches the search term
  // while($taggedRecipes->have_posts()) {
  //   $taggedRecipes->the_post();
  //   array_push($results['taggedRecipes'], array(
  //     'post_type' => 'taggedRecipe',
  //     'title' => get_the_title(),
  //     'permalink' => get_the_permalink()
  //   ));
  // }

  // return searched term for use on front-end
  // $results['term'] = sanitize_text_field($data['term']);

  // return $results;
// }



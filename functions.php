<?php

require get_theme_file_path('/includes/search-route.php');

function k6_files() {
  wp_enqueue_script('main-js', get_theme_file_uri('/build/index.js'));
  wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css2?family=Lato:wght@100;300;400;500;600;700;900&display=swap');
  wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
  wp_enqueue_style('k6_main_styles', get_theme_file_uri('/style.css'));
  wp_enqueue_style('k6_extra_styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('dashicons');

  // For dynamically setting k6.root_url prop to point to root
  // whether on prod or local. (example usage in Search.js)
  wp_localize_script(
    'main-js', 'k6', array(
      'root_url' => get_site_url()
    )
  );
}
add_action('wp_enqueue_scripts', 'k6_files');

function k6_post_types() {
  register_post_type('recipe', array(
    'supports' => array('excerpt', 'title', 'thumbnail', 'widget'),
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
    'menu_icon' => 'dashicons-food',
    'publicly_queryable' => true,
    'taxonomies'  => array( 'category' )
  ));
  register_post_type('ingredient', array(
    'supports' => array('title', 'editor', 'thumbnail'),
    'show_in_rest' => true,
    'rewrite' => array('slug' => 'ingredients'),
    'has_archive' => true,
    'menu_position' => 3,
    'public' => true,
    'labels' => array(
      'name' => 'Ingredient Profiles',
      'all_items' => 'All Ingredient Profiles',
      'add_new_item' => 'Add New Ingredient Profile',
      'edit_item' => 'Edit Ingredient Profile',
      'singular_name' => 'Ingredient Profile'
    ),
    'menu_icon' => 'dashicons-media-document'
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

function login_styles() {
  wp_enqueue_style( 'custom-styles', get_theme_file_uri('/build/style-index.css'));
  wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet');
}
add_action( 'login_enqueue_scripts', 'login_styles' );

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

// Enable "Widgets" admin section
function k6_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'twentytwentyone' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'twentytwentyone' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'k6_widgets_init' );

// Enable tags for recipes
function gp_register_taxonomy_for_object_type() {
    register_taxonomy_for_object_type( 'post_tag', 'recipe' );
};
add_action( 'init', 'gp_register_taxonomy_for_object_type' );


function custom_enter_title_text( $input ) {
  if ( 'recipe' === get_post_type() ) {
      return __( 'Add recipe title', 'wp-rig' );
  }
  return $input;
}

add_filter( 'enter_title_here', 'custom_enter_title_text' );




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



function register_taxonomy_cuisine() {
	 $labels = array(
		 'name'              => _x( 'Cuisines', 'taxonomy general name' ),
		 'singular_name'     => _x( 'Cuisine', 'taxonomy singular name' ),
		 'search_items'      => __( 'Search Cuisines' ),
		 'all_items'         => __( 'All Cuisines' ),
		 'parent_item'       => __( 'Parent Cuisine' ),
		 'parent_item_colon' => __( 'Parent Cuisine:' ),
		 'edit_item'         => __( 'Edit Cuisine' ),
		 'update_item'       => __( 'Update Cuisine' ),
		 'add_new_item'      => __( 'Add New Cuisine' ),
		 'new_item_name'     => __( 'New Cuisine Name' ),
		 'menu_name'         => __( 'Cuisine' ),
	 );
	 $args   = array(
		 'hierarchical'      => true, // make it hierarchical (like categories)
		 'labels'            => $labels,
		 'show_ui'           => true,
		 'show_admin_column' => true,
		 'query_var'         => true,
		 'rewrite'           => [ 'slug' => 'course' ],
	 );
	 register_taxonomy( 'cuisine', [ 'recipe' ], $args );
}
add_action( 'init', 'register_taxonomy_cuisine' );



// function wpse301755_change_tags_labels( $args, $taxonomy ) {
//     if ( 'post_tag' === $taxonomy ) {
//         $args['labels'] = array(
//             'name'          => 'Ingredients',
//             'singular_name' => 'Ingredient',
//             'menu_name'     => 'Ingredients',
//             'separate_items_with_commas' => 'Separate ingredients with commas.',
//             'choose_from_most_used' => 'Choose from the most used ingredients.'
//         );
//     }
//     return $args;
// }
// add_filter( 'register_taxonomy_args', 'wpse301755_change_tags_labels', 10, 2 );





function wpse_58799_remove_parent_category()
{
    if ( 'cuisine' != $_GET['taxonomy'] )
        return;

    $parent = 'parent()';

    if ( isset( $_GET['action'] ) )
        $parent = 'parent().parent()';

    ?>
        <script type="text/javascript">
            jQuery(document).ready(function($)
            {     
                $('label[for=parent]').<?php echo $parent; ?>.remove();       
            });
        </script>
    <?php
}
add_action( 'admin_head-edit-tags.php', 'wpse_58799_remove_parent_category' );


// function blah($original_excerpt) { 
//   if ( !is_search() ) return $original_excerpt; 
//   return get_post_meta(get_the_ID(), 'MYCUSTOMFIELD', true); 
// }

// add_filter( 'the_excerpt', 'blah');

add_filter("recipe_description_excerpt", function($excerpt, $prepare_excerpt, $permalink, $entry, $class) {
	$excerpt = get_field( 'description', $entry->ID );
   	return $excerpt; 
}, 10, 5);


function string_limit_words($string, $word_limit) {
   $words = explode(' ', $string, ($word_limit + 1));
	
   if(count($words) > $word_limit) {
      array_pop($words);
   }
	
   return implode(' ', $words);
}


function modify_excerpt($excerpt) {
   $label = "Read More";
   $limit = 50;
   $excerpt = string_limit_words( $excerpt, $limit) . '…';
   $excerpt .= '<a href="" class="avia-button av-readmore avia-icon_select-no avia-size-small avia-position-center avia-color-theme-color"><span class="avia_iconbox_title">' . $label . '</span></a>';
   return $excerpt;
}


// add_filter('get_the_excerpt', function($excerpt, $entry) {
//    $custom_excerpt = get_field( 'description', $entry->ID );
	
//    if ( $custom_excerpt ) {
//       $excerpt = modify_excerpt($custom_excerpt);
//    }
	
//    return $excerpt; 
// }, 10, 2);

function remove_h1_from_tinymce_formats($init) {
    // Only allow Paragraph, Heading 2, and Heading 3 (no Heading 1)
    $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6';
    return $init;
}
add_filter('tiny_mce_before_init', 'remove_h1_from_tinymce_formats');


function is_editing_single_custom_type($type) {
    if (is_admin() && isset($_GET['post'])) {
        $post_id = intval($_GET['post']);
        return get_post_type($post_id) === $type;
    }
    return false;
}

function custom_tinymce_formats_for_recipe($init) {
    if (is_editing_single_custom_type('recipe')) {
        // Only allow Paragraph, Heading 4, 5, 6 for recipes
        $init['block_formats'] = 'Paragraph=p;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6';
    }
    return $init;
}
add_filter('tiny_mce_before_init', 'custom_tinymce_formats_for_recipe');


function myplugin_remove_h1_for_ingredient_cpt() {
    global $post_type;

    // Only enqueue for the 'ingredient' custom post type
    if ($post_type === 'ingredient') {
        wp_enqueue_script(
            'myplugin-gutenberg-heading-filter',
            get_template_directory_uri() . '/js/gutenberg-heading-filter.js',
            array('wp-blocks', 'wp-hooks', 'wp-element', 'wp-edit-post'),
            false,
            true
        );
    }
}
add_action('enqueue_block_editor_assets', 'myplugin_remove_h1_for_ingredient_cpt');

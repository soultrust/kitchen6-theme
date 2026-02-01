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

  register_post_type('supplement', array(
    'supports' => array('title', 'editor', 'thumbnail'),
    'show_in_rest' => true,
    'rewrite' => array('slug' => 'supplements'),
    'has_archive' => true,
    'menu_position' => 4,
    'public' => true,
    'labels' => array(
      'name' => 'Supplements',
      'all_items' => 'All Supplements',
      'add_new_item' => 'Add Supplement',
      'edit_item' => 'Edit Supplement',
      'singular_name' => 'Supplement'
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
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
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
		 'public'            => true,
		 'rewrite'           => [ 'slug' => 'cuisine' ],
	 );
	 register_taxonomy( 'cuisine', [ 'recipe' ], $args );
}
add_action( 'init', 'register_taxonomy_cuisine' );

// Flush rewrite rules when theme is activated
function flush_rewrite_rules_on_activation() {
	register_taxonomy_cuisine();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'flush_rewrite_rules_on_activation' );

// Manual flush function - call this once to fix the 404 issue
function manual_flush_rewrite_rules() {
	if (isset($_GET['flush_rules']) && $_GET['flush_rules'] == 'true') {
		flush_rewrite_rules();
		echo '<div style="background: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border: 1px solid #c3e6cb;">Rewrite rules flushed successfully!</div>';
	}
}
add_action( 'init', 'manual_flush_rewrite_rules' );



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



// Widget for Latest Posts of a Custom Post Type

class Latest_Recipe_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'latest_recipe_widget',
            __('Latest Recipes', 'text_domain'),
            array('description' => __('Displays latest posts from the Recipe custom post type', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Latest Recipes', 'text_domain');
        $query = new WP_Query(array(
            'post_type' => 'recipe',
            'posts_per_page' => 50,
            'post_status' => 'publish'
        ));

        echo $args['before_widget'];
        if (!empty($title)) echo $args['before_title'] . $title . $args['after_title'];

        if ($query->have_posts()) {
            echo '<ul class="link-list">';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li class="link-list-item"><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No recipes found.</p>';
        }
        echo $args['after_widget'];
        wp_reset_postdata();
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Latest Recipes', 'text_domain');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
}

function register_latest_recipe_widget() {
    register_widget('Latest_Recipe_Widget');
}
add_action('widgets_init', 'register_latest_recipe_widget');




class Ingredient_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'ingredient_widget',
            __('Ingredient List', 'text_domain'),
            array('description' => __('Displays a list of Ingredients', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        $show_title = !empty($instance['show_title']);

        echo $args['before_widget'];
        if ($show_title) {
            echo $args['before_title'] . apply_filters('widget_title', 'Ingredient Profiles') . $args['after_title'];
        }

        // Query custom post type 'ingredient'
        $ingredient_query = new WP_Query(array(
            'post_type' => 'ingredient',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        if ($ingredient_query->have_posts()) {
            echo '<ul class="ingredient-list">';
            while ($ingredient_query->have_posts()) {
                $ingredient_query->the_post();
                // Each list item is a link to the single ingredient page
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No ingredients found.</p>';
        }
        wp_reset_postdata();

        echo $args['after_widget'];
    }

    public function form($instance) {
        $show_title = !empty($instance['show_title']);
        ?>
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_title')); ?>" name="<?php echo esc_attr($this->get_field_name('show_title')); ?>" <?php checked($show_title); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_title')); ?>"><?php esc_html_e('Show title', 'kitchen6'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['show_title'] = !empty($new_instance['show_title']);
        return $instance;
    }
}

// Register the widget
function register_ingredient_widget() {
    register_widget('Ingredient_Widget');
}
add_action('widgets_init', 'register_ingredient_widget');


class Supplement_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'supplement_widget',
            __('Supplements', 'text_domain'),
            array('description' => __('Displays a list of Supplements', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        $show_title = !empty($instance['show_title']);

        echo $args['before_widget'];
        if ($show_title) {
            echo $args['before_title'] . apply_filters('widget_title', 'Supplements') . $args['after_title'];
        }

        // Query custom post type 'supplement'
        $supplement_query = new WP_Query(array(
            'post_type' => 'supplement',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        if ($supplement_query->have_posts()) {
            echo '<ul class="ingredient-list">';
            while ($supplement_query->have_posts()) {
                $supplement_query->the_post();
                // Each list item is a link to the single supplement page
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No supplements found.</p>';
        }
        wp_reset_postdata();

        echo $args['after_widget'];
    }

    public function form($instance) {
        $show_title = !empty($instance['show_title']);
        ?>
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_title')); ?>" name="<?php echo esc_attr($this->get_field_name('show_title')); ?>" <?php checked($show_title); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_title')); ?>"><?php esc_html_e('Show title', 'kitchen6'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['show_title'] = !empty($new_instance['show_title']);
        return $instance;
    }
}

// Register the widget
function register_supplement_widget() {
    register_widget('Supplement_Widget');
}
add_action('widgets_init', 'register_supplement_widget');





class Recipe_List_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'recipe_list_widget',
            __('Recipe List', 'text_domain'),
            array('description' => __('Displays a list of all recipes in alphabetical order.', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        $show_title = !empty($instance['show_title']);

        echo $args['before_widget'];
        if ($show_title && !empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Query all recipes
        $recipes = new WP_Query(array(
            'post_type'      => 'recipe',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ));

        if ($recipes->have_posts()) {
            echo '<ul class="recipe-list-widget">';
            while ($recipes->have_posts()) {
                $recipes->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>No recipes found.</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title      = !empty($instance['title']) ? $instance['title'] : __('Recipes', 'text_domain');
        $show_title = !empty($instance['show_title']);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_title')); ?>" name="<?php echo esc_attr($this->get_field_name('show_title')); ?>" <?php checked($show_title); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_title')); ?>"><?php esc_html_e('Show title', 'kitchen6'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title']      = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['show_title'] = !empty($new_instance['show_title']);
        return $instance;
    }
}

// Register the widget
function register_recipe_list_widget() {
    register_widget('Recipe_List_Widget');
}
add_action('widgets_init', 'register_recipe_list_widget');


































class Cuisine_List_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'cuisine_list_widget',
            __('Cuisine List', 'text_domain'),
            array('description' => __('Displays a list of all cuisines', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        $show_title = !empty($instance['show_title']);

        echo $args['before_widget'];
        if ($show_title && !empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $terms = get_terms(array(
            'taxonomy' => 'cuisine',
            'hide_empty' => false,
        ));

        if (!empty($terms) && !is_wp_error($terms)) {
            echo '<ul class="cuisine-list">';
            foreach ($terms as $term) {
                $link = get_term_link($term);
                if (!is_wp_error($link)) {
                    echo '<li><a href="' . esc_url($link) . '">' . esc_html($term->name) . '</a></li>';
                }
            }
            echo '</ul>';
        } else {
            echo '<p>' . __('No cuisines found.', 'text_domain') . '</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title      = !empty($instance['title']) ? $instance['title'] : __('Cuisines', 'text_domain');
        $show_title = !empty($instance['show_title']);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_title')); ?>" name="<?php echo esc_attr($this->get_field_name('show_title')); ?>" <?php checked($show_title); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_title')); ?>"><?php esc_html_e('Show title', 'kitchen6'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title']      = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['show_title'] = !empty($new_instance['show_title']);
        return $instance;
    }
}

// Register the widget
function register_cuisine_list_widget() {
    register_widget('Cuisine_List_Widget');
}
add_action('widgets_init', 'register_cuisine_list_widget');


/**
 * Browse Accordion Widget
 * Configurable accordion: Cuisines, Alphabetical Order, Ingredient Profiles, Supplements.
 * Admin can choose which sections to show. Only one section open at a time.
 */
class Browse_Accordion_Widget extends WP_Widget {

	private static $sections = array(
		'cuisines'     => 'Cuisines',
		'alphabetical' => 'Alphabetical Order',
		'ingredients'  => 'Ingredient Profiles',
		'supplements'  => 'Supplements',
	);

	public function __construct() {
		parent::__construct(
			'browse_accordion_widget',
			__('Browse Accordion', 'kitchen6'),
			array('description' => __('Configurable accordion: choose which sections to display.', 'kitchen6'))
		);
	}

	public function widget($args, $instance) {
		$default_open = !empty($instance['default_open']) ? $instance['default_open'] : 'none';
		$enabled      = $this->get_enabled_sections($instance);

		if (empty($enabled)) {
			return;
		}

		// If default_open is disabled, fall back to first enabled or none
		if ($default_open !== 'none' && !in_array($default_open, $enabled, true)) {
			$default_open = $enabled[0];
		}

		echo $args['before_widget'];
		?>
		<div class="k6-accordion-widget-wrapper">
		<div class="k6-browse-accordion" data-default-open="<?php echo esc_attr($default_open); ?>">
		<?php
		foreach (self::$sections as $section_id => $label) {
			if (!in_array($section_id, $enabled, true)) {
				continue;
			}
			$content_id = 'k6-accordion-' . $section_id;
			?>
			<div class="k6-accordion-section" data-section="<?php echo esc_attr($section_id); ?>">
				<button type="button" class="k6-accordion-trigger" aria-expanded="false" aria-controls="<?php echo esc_attr($content_id); ?>"><?php echo esc_html($label); ?></button>
				<div id="<?php echo esc_attr($content_id); ?>" class="k6-accordion-content" role="region">
					<div class="k6-accordion-inner">
						<?php $this->render_section($section_id); ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		</div>
		</div>
		<?php

		echo $args['after_widget'];
	}

	private function get_enabled_sections($instance) {
		$enabled = array();
		foreach (array_keys(self::$sections) as $section_id) {
			$key = 'show_' . $section_id;
			if (!isset($instance[$key]) || $instance[$key]) {
				$enabled[] = $section_id;
			}
		}
		return $enabled;
	}

	private function render_section($section_id) {
		switch ($section_id) {
			case 'cuisines':
				$this->render_cuisines();
				break;
			case 'alphabetical':
				$this->render_alphabetical();
				break;
			case 'ingredients':
				$this->render_ingredients();
				break;
			case 'supplements':
				$this->render_supplements();
				break;
		}
	}

	private function render_cuisines() {
		$terms = get_terms(array('taxonomy' => 'cuisine', 'hide_empty' => false));
		if (!empty($terms) && !is_wp_error($terms)) {
			echo '<ul class="k6-accordion-list cuisine-list">';
			foreach ($terms as $term) {
				$link = get_term_link($term);
				if (!is_wp_error($link)) {
					echo '<li><a href="' . esc_url($link) . '">' . esc_html($term->name) . '</a></li>';
				}
			}
			echo '</ul>';
		} else {
			echo '<p>' . esc_html__('No cuisines found.', 'kitchen6') . '</p>';
		}
	}

	private function render_alphabetical() {
		$recipes = new WP_Query(array(
			'post_type'      => 'recipe',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		));
		if ($recipes->have_posts()) {
			echo '<ul class="k6-accordion-list recipe-list">';
			while ($recipes->have_posts()) {
				$recipes->the_post();
				echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
			}
			echo '</ul>';
			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__('No recipes found.', 'kitchen6') . '</p>';
		}
	}

	private function render_ingredients() {
		$query = new WP_Query(array(
			'post_type'      => 'ingredient',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		));
		if ($query->have_posts()) {
			echo '<ul class="k6-accordion-list ingredient-list">';
			while ($query->have_posts()) {
				$query->the_post();
				echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
			}
			echo '</ul>';
			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__('No ingredients found.', 'kitchen6') . '</p>';
		}
	}

	private function render_supplements() {
		$query = new WP_Query(array(
			'post_type'      => 'supplement',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		));
		if ($query->have_posts()) {
			echo '<ul class="k6-accordion-list supplement-list">';
			while ($query->have_posts()) {
				$query->the_post();
				echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
			}
			echo '</ul>';
			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__('No supplements found.', 'kitchen6') . '</p>';
		}
	}

	public function form($instance) {
		$title        = !empty($instance['title']) ? $instance['title'] : __('Browse Recipes', 'kitchen6');
		$default_open = !empty($instance['default_open']) ? $instance['default_open'] : 'none';
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'kitchen6'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<p><strong><?php esc_html_e('Show sections:', 'kitchen6'); ?></strong></p>
		<p>
			<?php foreach (self::$sections as $section_id => $label) : ?>
				<?php
				$key        = 'show_' . $section_id;
				$is_checked = !isset($instance[$key]) || $instance[$key];
				?>
				<label style="display: block; margin-bottom: 0.35em;">
					<input class="checkbox" type="checkbox" name="<?php echo esc_attr($this->get_field_name($key)); ?>" <?php checked($is_checked); ?>>
					<?php echo esc_html($label); ?>
				</label>
			<?php endforeach; ?>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('default_open')); ?>"><?php esc_html_e('Default open section:', 'kitchen6'); ?></label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('default_open')); ?>" name="<?php echo esc_attr($this->get_field_name('default_open')); ?>">
				<option value="none" <?php selected($default_open, 'none'); ?>><?php esc_html_e('None (all closed)', 'kitchen6'); ?></option>
				<?php foreach (self::$sections as $section_id => $label) : ?>
					<option value="<?php echo esc_attr($section_id); ?>" <?php selected($default_open, $section_id); ?>><?php echo esc_html($label); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title']        = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		$instance['default_open'] = (!empty($new_instance['default_open']) && in_array($new_instance['default_open'], array('none', 'cuisines', 'alphabetical', 'ingredients', 'supplements'), true))
			? $new_instance['default_open']
			: 'none';
		foreach (array_keys(self::$sections) as $section_id) {
			$key                  = 'show_' . $section_id;
			$instance[$key]       = !empty($new_instance[$key]);
		}
		return $instance;
	}
}

function register_browse_accordion_widget() {
	register_widget('Browse_Accordion_Widget');
}
add_action('widgets_init', 'register_browse_accordion_widget');
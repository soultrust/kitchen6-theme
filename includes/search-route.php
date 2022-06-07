<?php

function register_search() {
  register_rest_route('k6/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'search_results'
  ));
}
add_action('rest_api_init', 'register_search');

function search_results($data) {
  $recipes = new WP_Query(array(
    'post_type' => 'recipe',
    's' => sanitize_text_field($data['term'])
  ));
  wp_reset_postdata();

  $results = array(
    'recipes' => array(),
    'tags' => array(),
    'taggedRecipes' => array(),
    'ingredients' => array(),
    'term' => ''
  );

  while($recipes->have_posts()) {
    $recipes->the_post();

    if (get_post_type() === 'recipe') {
      array_push($results['recipes'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
      ));
    }
  }

  $tags = get_tags(array(
    'search' => sanitize_text_field($data['term'])
  ));

  // GET tags (tag archive pages) that have search term in the tag name
  foreach ($tags as &$tag) {
    array_push($results['tags'], array(
      'post_type' => 'tag',
      'name' => $tag->name,
      'permalink' => get_tag_link($tag->term_id)
    ));
  }

  $taggedRecipes = new WP_Query(array(
    'post_type' => 'recipe',
    'tag' => sanitize_text_field($data['term'])
  ));

  // GET recipes that have a tag that matches the search term
  while($taggedRecipes->have_posts()) {
    $taggedRecipes->the_post();
    array_push($results['taggedRecipes'], array(
      'post_type' => 'taggedRecipe',
      'title' => get_the_title(),
      'permalink' => get_the_permalink()
    ));
  }

  $ingredients = new WP_Query(array(
    'post_type' => 'ingredient',
    's' => sanitize_text_field($data['term'])
  ));

  // GET ingredient profiles that contain the search term
  while($ingredients->have_posts()) {
    $ingredients->the_post();
    array_push($results['ingredients'], array(
      'post_type' => 'ingredient',
      'title' => get_the_title(),
      'permalink' => get_the_permalink()
    ));
  }

  // return searched term for use on front-end
  $results['term'] = sanitize_text_field($data['term']);

  return $results;
}
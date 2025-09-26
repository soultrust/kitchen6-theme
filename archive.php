<?php
  get_header();
?>
<main id="primary" class="site-main">
<h1 class="archive-title"><?php the_archive_title(); ?></h1>
	<?php

$tag_args = array(
	'post_type' => 'recipe',
	'tag' => single_tag_title('', false)
);
$category_args = array(
	'post_type' => 'recipe',
	'category' => single_cat_title('', false)
);

if (is_tag()) {
	$recipes = new WP_Query($tag_args);
}
elseif (is_category()) {
	$recipes = new WP_Query($category_args);
}
elseif (is_tax()) {
	// Handle custom taxonomies
	$current_term = get_queried_object();
	$taxonomy_args = array(
		'post_type' => 'recipe',
		'tax_query' => array(
			array(
				'taxonomy' => $current_term->taxonomy,
				'field'    => 'slug',
				'terms'    => $current_term->slug,
			),
		),
		'posts_per_page' => -1,
	);
	$recipes = new WP_Query($taxonomy_args);
}
?>
<ul class="link-list">
	<?php
while ($recipes->have_posts()) {
	$recipes->the_post(); ?>
	<li class="link-list-item"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php 
} ?></ul>
</main>
<?php
	wp_reset_postdata();
	get_sidebar();
	get_footer();
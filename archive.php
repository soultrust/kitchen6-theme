<?php
  get_header();
?>
<main id="primary" class="site-main">
<h2 class="archive-title"><?php the_archive_title(); ?></h2>
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
if (is_category()) {
	$recipes = new WP_Query($category_args);
}
?>
<ul class="links-list">
	<?php
while ($recipes->have_posts()) {
	$recipes->the_post(); ?>
	<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php 
} ?></ul>
</main>
<?php
	wp_reset_postdata();
	get_sidebar();
	get_footer();
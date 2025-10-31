<?php
  get_header();
?>
<main id="primary" class="site-main">
	<?php




	while ( have_posts() ) {
		the_post(); ?>
		<h2 class="page-title"><?php 
    $post_type_obj = get_post_type_object(get_post_type());
echo $post_type_obj->labels->singular_name;

?>: <?php the_title(); ?></h2>
    <p><?php the_content(); ?></p>
		<?php }
    echo paginate_links(); ?>
</main>
<div id="sidebar">
	<div class="recently-added recently-added-ingredients">
    <h3>INGREDIENT PROFILES</h3>
    <ul class="link-list">
    <?php
    $recipes = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'ingredient'
    ));

    while($recipes->have_posts()) {
      $recipes->the_post(); ?>
      <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
    <?php } wp_reset_postdata(); ?>
    </ul>
  </div>
</div>
<?php
	get_footer();
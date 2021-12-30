<?php 
  get_header();
?>

<main class="site-main">
  <h3>RECENTLY ADDED RECIPES</h3>
  <ul class="links-list">
  <?php
  $recipes = new WP_Query(array(
    'posts_per_page' => -1,
    'post_type' => 'recipe'
  ));

  while($recipes->have_posts()) {
    $recipes->the_post(); ?>
    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
  <?php } wp_reset_postdata(); ?>
  </ul>
</main>

<?php
  get_sidebar();
  get_footer();
<?php 
  get_header();
?>

<main class="site-main">
  <div class="recently-added recently-added-recipes">
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
  </div>

  <div class="recently-added recently-added-ingredients">
    <h3>RECENTLY ADDED INGREDIENT PROFILES</h3>
    <ul class="links-list">
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
</main>

<?php
  get_sidebar();
  get_footer();
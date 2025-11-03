<?php 
  get_header();
?>

<main class="site-main">
    <?php
      $recipes = new WP_Query(array(
        'posts_per_page' => 15,
        'post_type' => 'recipe'
      ));

      while($recipes->have_posts()) {
        $recipes->the_post(); ?>
        <section class="entry">
        
          <div class="entry-featured-img" style="background-image: url(<?php the_post_thumbnail_url('soultrust-featured') ?>);">
          </div>
   

        <div class="recipe-info">
          <header class="entry-header">
          <?php the_title('<h2 class="entry-title"><a href="'. esc_url(get_permalink()) . '">', '</a></h2>'); ?>
            <a href="<?php the_permalink(); ?>" class="link-recipe"><i class="dashicons dashicons-format-aside"></i>Recipe</a>
          </header>
          <div class="description">
            <?php if (strlen(get_field('description')) > 0) { ?>
            <?php the_field('description'); ?>
            <?php } ?>
          </div>
        </div>
       
          </section>  
         
         
  
        
      <?php } ?>  
       
         



      

 
</main>

<?php
  get_sidebar();
  get_footer();
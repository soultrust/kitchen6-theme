<?php
  get_header();
?>
<main id="primary" class="site-main">
	<?php

	while ( have_posts() ) {
		the_post(); ?>

		// get_template_part( 'template-parts/content/entry', get_post_type() );
		<h2><?php the_title(); ?></h2>
    <p><?php the_content(); ?></p>
		<?php } ?>
</main>
<?php
	get_sidebar();
	get_footer();
<?php
  get_header();
?>
<main id="primary" class="site-main">
	<?php

	while ( have_posts() ) {
		the_post(); ?>
		<h2><?php the_title(); ?></h2>
    <p><?php the_content(); ?></p>
		<?php }
    echo paginate_links(); ?>
</main>
<?php
	get_footer();
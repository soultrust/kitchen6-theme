<?php
  get_header();
?>
<main id="primary" class="site-main recipe-layout">
	<?php

	while ( have_posts() ) {
		the_post(); 




if (has_post_thumbnail()) { ?>
<div class="entry-feature-img" style="background-image: url(<?php the_post_thumbnail_url('newnormalize-featured') ?>);">
</div>
<?php } ?>
<div class="recipe-info">
  <?php the_title('<h1 class="recipe-title">', '</h1>'); ?>
  <div class="recipe-meta">
    <?php if (get_field('source')) { ?>
    Source: <?php if (get_field('source_url')) { ?>
    <a href="<?php the_field('source_url'); ?>" target="_blank">
        <?php the_field('source'); ?>
    </a><?php } else {
                the_field('source'); ?>
   <?php }
    }

        $show_categories = true;
        $categories = wp_get_post_categories(get_the_ID());

        // We don't want to show the categories if there is
        // a single category and it is "uncategorized"
        if (count($categories) == 1 && in_array(1, $categories)) :
            $show_categories = false;
        endif;

				$taxonomies = wp_list_filter(
					get_object_taxonomies($post, 'objects'),
					array(
							'public' => true,
					)
			);
			
			?>
			<div class="entry-taxonomies <?php echo has_category(null, get_the_ID()) && $show_categories ? '' : 'hide-categories'; ?>">
			<?php
			// Show terms for all taxonomies associated with the post.
			foreach ( $taxonomies as $taxonomy ) {
				// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			
					/* translators: separator between taxonomy terms */
					$separator = _x(', ', 'list item separator', 'wp-rig');
			
					switch ( $taxonomy->name ) {
					case 'category':
							$class            = 'category-links term-links';
							$list             = get_the_category_list(esc_html( $separator ), '', $post->ID );
							/* translators: %s: list of taxonomy terms */
							$placeholder_text = __('Categories: %s', 'wp-rig');
							break;
					case 'post_tag':
							$class            = 'tag-links term-links';
							$list             = get_the_tag_list('', esc_html($separator), '', $post->ID);
							/* translators: %s: list of taxonomy terms */
							$placeholder_text = __('Tags: %s', 'wp-rig');
							break;
					default:
							$class            = str_replace('_', '-', $taxonomy->name) . '-links term-links';
							$list             = get_the_term_list($post->ID, $taxonomy->name, '', esc_html($separator), '');
							$placeholder_text = sprintf(
							/* translators: %s: taxonomy name */
									__('%s:', 'wp-rig'),
									$taxonomy->labels->name // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							);
					}
			
					if (empty($list)) {
							continue;
					}
					?>
			<span class="<?php echo esc_attr($class); ?>">
					<?php
							printf(
									esc_html($placeholder_text),
									$list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							);
					?>
			</span>
					<?php
			}
			?>
			</div><?php


        if (get_field('prep_time')) { ?>
          <span class="time-field">Prep: </span><?php the_field('prep_time');
        }

        if (get_field('cook_time')) { ?>
          <span class="time-field">Cook: </span><?php the_field('cook_time');
        }

        if (get_field('additional_time')) { ?>
          <span class="time-field">Additional: </span><?php the_field('additional_time');
        }

        if (get_field('total_time')) { ?>
          <span class="time-field">Total: </span><?php the_field('total_time');
        }

        if (get_field('servings')) { ?>
          <div class="servings">Servings: <?php the_field('servings'); ?></div><?php
        } ?>

        </div>
        <?php if (strlen(get_field('description')) > 0) { ?>
      <section class="notes text-block">
          <?php the_field('description');?>
      </section>
    <?php } ?>
        </div>









		<div class="rich-text-panel ingredients">
      <h3 class="panel-title">Ingredients</h3>
      <?php the_field('ingredients'); ?>
    </div>

    <div class="rich-text-panel directions">
      <h3 class="panel-title">Directions</h3>
      <?php the_field('directions'); ?>
    </div>

    <?php if (get_field('notes')) { ?>
      <div class="rich-text-panel notes">
        <h3 class="panel-title">Notes</h3>
        <?php the_field('notes'); ?>
      </div>
    <?php } ?>

		<?php } ?>
</main>
<?php
	get_footer();
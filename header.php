<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kitchen6 Recipe Manager</title>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <header class="site-header">

    <?php if (is_front_page()) { ?>
    <h1 class="site-title">
      <?php echo file_get_contents(get_stylesheet_directory_uri() . '/images/kitchen6-logo.svg') ?>
    </h1>
    <?php } else { ?>
    <h1 class="site-title">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <?php echo file_get_contents(get_stylesheet_directory_uri() . '/images/kitchen6-logo.svg') ?>
      </a>
    </h1>
    <?php } ?>

    <div class="search">
      <div class="search-field" id="search-field">
        <input type="text" class="search-input" id="search-input" placeholder="Search Recipes and Tags" autocomplete="off" />
      </div>
      <div class="search-trigger" id="search-trigger">
        <div class="search-trigger-txt" id="search-trigger-text">
          <span>Search Recipes</span>
        </div>
        <div class="dashicons dashicons-search search-icon"></div>
      </div>
    </div>
  </header>

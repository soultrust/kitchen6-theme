<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kitchen6 Recipe Manager</title>
  <link rel="apple-touch-icon" sizes="180x180" href="/wp-content/themes/kitchen6/favicons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/wp-content/themes/kitchen6/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/wp-content/themes/kitchen6/favicons/favicon-16x16.png">
  <!-- <link rel="manifest" href="/wp-content/themes/kitchen6/site.webmanifest"> -->
  <link rel="manifest" href="/manifest.json">
  <link rel="mask-icon" href="/wp-content/themes/kitchen6/favicons/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="layout">
  <header class="site-header">

    <?php if (is_front_page()) { ?>
    <div class="site-title">
      <?php echo file_get_contents(get_stylesheet_directory_uri() . '/images/kitchen6-logo.svg') ?>
    </div>
    <?php } else { ?>
    <div class="site-title">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <?php echo file_get_contents(get_stylesheet_directory_uri() . '/images/kitchen6-logo.svg') ?>
      </a>
    </div>
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

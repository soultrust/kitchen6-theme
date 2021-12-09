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
    <h1 class="site-title">
      <?php echo file_get_contents(get_stylesheet_directory_uri() . '/images/kitchen6-logo.svg') ?>
    </h1>
    <div class="search-input">
      <input type="text" class="search-field" id="search-field" placeholder="Search Recipes and Tags" autocomplete="off" />
    </div>
    <div class="search" id="js-search-trigger">
      <div class="search-text">
        <span>Search</span>
        <span>Recipes</span>
      </div>
      <div class="dashicons dashicons-search search-icon"></div>
    </div>
  </header>

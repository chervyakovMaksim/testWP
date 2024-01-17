<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php the_title(); ?></title>
    <meta name="viewport" content="width=device-width"/>
    <?php wp_head(); ?>
  </head>
  <body>
    <header class="header">
      <a href="<?php echo home_url(); ?>">Главная</a>
    </header>
<!doctype html>
<html class="no-js" lang="">

<!--------------------------
Version: 1.0
----------------------------->

<head>
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JXLH1BVL55"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-JXLH1BVL55');
</script>
  <?php wp_head(); ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Legenda - Creative Production Services</title>
  <meta name="viewport" content="width=device-width, initial-scale=.9, maximum-scale=1.0, user-scalable=0" />
  <meta property="og:title" content="We combine in-camera experince and virutal design to bring your creative work to life.">
  <meta itemprop="name" content="Legenda - Marketing implementation and advertising production">
  <meta name="description" content="Legenda - Marketing implementation and advertising production studio. Our work encompasses photography, motion, websites and digital experiences, graphics and identity, products and packaging.">
  <meta property="og:url" content="https://legenda.co/">
  <meta property="og:image" content="">
  <link rel="manifest" href="">
</head>

<body>
  <div class="wrapper" id="Header">
    <div class="header-container main-grid">



      <!--
      Main Nav
      -->
      <?php require('inc/main-nav.php') ?>
      <!--hero copy-->
      <?php
      $hero = new WP_Query(array(
        'post_type' => array('post', 'folio'),
        'cat' => '23'
      ));
      ?>
       <?php while ( $hero->have_posts() ) :
        $hero->the_post(); ?>
      <div class="tag-and-title grid-bottom grid-bottom-right nav-toggle targets" id="TagAndTitle">
      <a href="<?php the_permalink(); ?>">
        <!-- get tag and title content -->
       <h3 class="title"><?php the_title(); ?></h3>
       <h4 class="tag"> <?php the_excerpt(); ?></h4></a>
       </div>
      <!--HERO IMAGE-->
      
     <a href="<?php the_permalink(); ?>" class="main-grid-bleed"> <?php the_post_thumbnail('full');
  endwhile; ?></a>
    
    <!-- ends container -->
      </div>
<?php
/**
 * The template for displaying all single posts and attachments
 *
 */ ?>

<?php get_header('single'); ?>
<!--post content-->
<?php 

while (have_posts()) { 
  the_post(); ?>

<article class="main-grid-full">

  <header>
    <div class="wp-block-columns">
      <div class="wp-block-column"><?php the_title('<h1>', '</h1>'); ?>
        <h5>
          <?php the_category($seperator=',');?>
        </h5>
      </div>
      <div class="wp-block-column">
        <h3><?php the_excerpt(); ?></h3>
      </div>
    </div>
  </header>

  <main>
    <?php the_content();?>

  </main>
  <div class="pagnation">
      <h5><?php previous_post_link(); ?></h5>
      <h5><?php next_post_link(); ?></h5>
    </div>
</article>
</div>
<?php get_footer(); }?>
</body>

</html>

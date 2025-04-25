<h3 class="work-cat-title"><?php echo $cat->name; ?></h3>
<div class="work-cat-container">
  <div class="work-feed-container">
    <?php
    $folio = new WP_Query(
      array(
        'post_type' => 'folio'
      )
    );
    while ($folio->have_posts()) {
      $folio->the_post(); ?>
      <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?>
        <div>
          <h4><?php the_title(); ?></h4>
        </div>
      </a>
    <?php
    }
    wp_reset_postdata();
    ?>
  </div>
  <div class="slide-left btns transparent">
    <div class="chevron-left"></div>
  </div>
  <div class="slide-right btns transparent">
    <div class="chevron-right"></div>
  </div>

</div>
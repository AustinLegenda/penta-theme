<?php
/** 
 * Template Name: folio-filter template 
 * source: https://wordpress.stackexchange.com/questions/185004/looping-through-posts-per-category-gives-same-posts-for-each-category
 * */ 
get_header('single'); 
?>
<article class="main-grid-full">
<?php
  $categories = get_categories(array(
    'exclude_tree' => array('3'),
    'hide_empty' => array('0'),
    'exclude' => array('1','8','4','23')
  )); ?>
<?php foreach ($categories as $category) : ?>
<h2 class="work-cat-title"><?php echo $category->cat_name; ?></h2>
<div class="work-cat-container">
  <div class="work-feed-container">
  <?php $args = array(
                'post_type' => array('folio','post'),
                'cat' => $category->cat_ID,
                'posts_per_page' => 5
            ); 
            
            // Clear transient cache
            delete_transient('category_posts_' . $category->cat_ID);
?>  
            <?php if (false === ( $category_posts_query = get_transient( 'category_posts_' . $category->cat_ID ) ) ) {
                $category_posts_query = new WP_Query($args);
                set_transient( 'category_posts_' . $category->cat_ID, $category_posts_query, 30 * DAY_IN_SECONDS);
              } ?>
             
    <?php while($category_posts_query->have_posts()) : ?>
                <?php $category_posts_query->the_post(); ?>
      <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?>
        <div>
          <h4><?php the_title(); ?></h4>
        </div>
      </a>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
  </div>
  <div class="slide-left btns transparent">
    <div class="chevron-left"></div>
  </div>
  <div class="slide-right btns transparent">
    <div class="chevron-right"></div>
  </div>

</div>

  <?php endforeach; ?>

  </article>
      </div>
  <?php get_footer(); ?>
  
</body>

</html>
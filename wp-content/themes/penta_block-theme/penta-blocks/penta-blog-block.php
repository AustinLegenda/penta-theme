<?php
/**
 * Front‑end template for Penta Blog Block using jQuery AJAX.
 *
 * Expects $attributes['menuLocation'] and ['headingTag'] in scope.
 */

// 1) Attributes
$menu_location = $attributes['menuLocation'] ?? 'catNavOne';
$heading_tag   = in_array( $attributes['headingTag'] ?? 'h5', [ 'h2','h3','h4','h5','h6' ], true )
    ? $attributes['headingTag']
    : 'h5';

// 2) Load menu items
$locations = get_nav_menu_locations();
if ( empty( $locations[ $menu_location ] ) ) {
    echo '<p>No menu assigned.</p>'; return;
}
$menu_obj   = wp_get_nav_menu_object( $locations[ $menu_location ] );
$menu_items = wp_get_nav_menu_items( $menu_obj->term_id );
if ( empty( $menu_items ) ) {
    echo '<p>Menu is empty.</p>'; return;
}

// 3) Extract category IDs for “All”
$all_ids = [];
foreach ( $menu_items as $item ) {
    if ( 'category' === $item->object ) {
        $all_ids[] = (int) $item->object_id;
    }
}


if ( empty( $all_ids ) ) {
    echo '<p>No categories in menu.</p>'; return;
}


?>

<div class="penta-blog-block">
  <!-- Category Menu -->
  <nav class="cat-menu-container main-grid-full">
    <ul>
      <li>
        <a href="#" class="cat-menu-item" data-category="">
          <h5>All</h5>
        </a>
      </li>
      <?php foreach ( $menu_items as $item ) :
        if ( 'category' !== $item->object ) continue;
        $cat_id = (int) $item->object_id;
      ?>
        <li>
          <a href="#" class="cat-menu-item" data-category="<?php echo $cat_id; ?>">
            <h5><?php echo esc_html( $item->title );?></h5>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <!-- Posts Container -->
  <div class="main-grid">
    <article class="post-container main-grid-full">
      <?php
      // Initial load: show all
      $initial_query = new WP_Query( [
        'post_type'      => 'any',
        'posts_per_page' => 20,
        'category__in'   => $all_ids,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
      ] );
      if ( $initial_query->have_posts() ) :
        while ( $initial_query->have_posts() ) : $initial_query->the_post();
          $thumb = get_the_post_thumbnail_url( get_the_ID(), 'large' );
      ?>
        <div class="post-item">
          <a href="<?php the_permalink(); ?>">
            <div class="post-img-container">
              <?php if ( $thumb ) : ?>
                <img src="<?php echo esc_url( $thumb ); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
              <?php else : ?>
                <div class="placeholder">No Image</div>
              <?php endif; ?>
            </div>
            <div class="post-intro">
              <h3><?php the_title(); ?></h3>
              <div><?php the_excerpt(); ?></div>
            </div>
          </a>
        </div>
      <?php
        endwhile;
        wp_reset_postdata();
      else :
        echo '<p>No posts found.</p>';
      endif;
      ?>
    </article>
  </div>
</div>

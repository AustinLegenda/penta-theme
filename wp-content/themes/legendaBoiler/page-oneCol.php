<?php

/** 
 * Template Name: single column, full width 
 * */
get_header('single'); ?>

<!-- Page Content-->
<article class="main-grid-full">
  <?php
  $Post = get_post();
  ?>
  <main>
    <?php echo apply_filters('the_content', $Post->post_content);
    ?> </main>
</article>
</div>
<?php get_footer(); ?>
</body>

</html>
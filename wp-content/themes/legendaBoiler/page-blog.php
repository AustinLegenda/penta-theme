<?php

/** 
 * Template Name: Blog-Feed Template 
 * */
get_header('single');
?>
            <!--
Secondary Cat Navigation Menu
-->
<?php require('inc/second-nav.php'); ?>

<!--post titles should end up here-->
<article class="post-container main-grid-full">

    <?php

    $args = new WP_Query(
        array(
            'posts_per_page' => -1
        )
    );
    while ($args->have_posts()) {
        $args->the_post(); ?>
        <?php require('inc/snippet-post.php'); ?>
    <?php
    }
    wp_reset_postdata();
    ?>

</article>
</div>
    <?php get_footer(); ?>

</body>

</html>
<?php
echo '<h1>Loaded single-estimate.php</h1>';
get_header('single');

if (have_posts()) :
    while (have_posts()) : the_post(); ?>

        <div class="estimate-wrapper">
            <h1><?php the_title(); ?></h1>
            <p><strong>Description:</strong> <?php the_content(); ?></p>
        </div>

<?php endwhile; endif;

get_footer();

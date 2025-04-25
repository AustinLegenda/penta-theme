<?php
/** 
 * Template Name: Folio Grid Filter Template 
 */
get_header('single');
?>

<!-- Category Navigation -->
<div class="cat-menu-container main-grid-full">
    <ul>
        <li>
            <a class="cat-menu-item" data-category="all" href="#">All</a>
        </li>
        <?php
        $categories = get_categories(array(
            'exclude_tree' => array('3'),
            'hide_empty' => array('0'),
            'exclude' => array('1', '8', '4', '23')
        ));
        foreach ($categories as $cat) : ?>
            <li>
                <a class="cat-menu-item" data-category="<?php echo $cat->term_id; ?>" href="#">
                    <?php echo $cat->name; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- AJAX Target Container -->
<article class="main-grid-full target-container">
    <div class="folio-wrapper">
        <div class="folio-container nav-toggle">
            <?php
            $x = 0;
            $folio = new WP_Query(array(
                'post_type' => array('post', 'folio'),
                'cat' => '8',
                'order' => 'DESC',
                'posts_per_page' => -1
            ));

            while ($folio->have_posts()) :
                $folio->the_post();
                $x++;
            ?>
                <div class="item-folio">
                    <?php require('inc/snippet-folio.php'); ?>
                </div>

                <?php if ($x % 5 == 0) : ?>
                    </div><div class="folio-container nav-toggle">
                <?php endif; ?>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
    </div>
</article>

<?php get_footer(); ?>
</body>
</html>

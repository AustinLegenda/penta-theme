<div class="wrapper" id="PentaHeader">
    <div class="header-container main-grid" style="color: <?php echo esc_attr($attributes['textColor']); ?>;">

        <?php require get_template_directory() . '/penta-blocks/penta-nav.php'; ?>

        <?php
        $hero = new WP_Query(array(
            'post_type' => array('post', 'folio'),
            'cat' => '23'
        ));
        ?>
        <?php while ($hero->have_posts()) :
            $hero->the_post(); 
            ?>
            <div class="<?php echo esc_attr($attributes['gridClass']  ?? 'grid-bottom grid-bottom-right');  ?> tag-and-title nav-toggle targets" id="TagAndTitle">
                <a href="<?php the_permalink(); ?>">
                    <h3 class="title"><?php the_title(); ?></h3>
                    <h3 class="tag"> <?php the_excerpt(); ?></h3>
                </a>
            </div>

            <a href="<?php the_permalink(); ?>" class="main-grid-bleed">
                 <?php the_post_thumbnail('full'); endwhile; ?>
                </a>


    </div>
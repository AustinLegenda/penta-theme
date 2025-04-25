<div class="cat-menu-container main-grid-full">
    <ul>
        <?php
        $categories = get_categories(array(
           'exclude_tree' => array('4'),
           'exclude' => array('1', '3', '23')
        )); ?>
        <li>
            <a class="cat-menu-item" href="">
                All
            </a>
        </li>
        <?php foreach ($categories as $cat) : ?>
            <li>
                <a class="cat-menu-item" data-category="<?php echo $cat->term_id; ?>" href="<?php echo get_category_link($cat->term_id); ?>">
                    <?php echo $cat->name; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</div>
<div class="wrapper" id="PentaHeader">
    <div class="header-container main-grid" style="color: <?php echo esc_attr($attributes['textColor'] ?? '#222'); ?>;">
        <?php require get_template_directory() . '/penta-blocks/penta-nav.php'; ?>

        <div class="<?php echo esc_attr($attributes['gridClass'] ?? 'grid-bottom grid-bottom-right'); ?> tag-and-title nav-toggle targets" id="TagAndTitle">
        <a href="#" ><h3 class="title"><?php echo esc_html($attributes['title'] ?? ''); ?></h3>
            <h3 class="tag"><?php echo esc_html($attributes['excerpt'] ?? ''); ?></h3></a>
        </div>

        <?php if (!empty($attributes['heroImage']['url'])) : ?>
            <a href="<?php echo esc_html($attributes['linkURL'] ?? '#'); ?>" target="_self" class="main-grid-bleed hero-img">
                <img
                    src="<?php echo esc_url($attributes['heroImage']['url']); ?>"
                    alt="<?php echo esc_attr($attributes['heroImage']['alt'] ?? ''); ?>"
                    style="height: 100vh; width: 100%; object-fit: cover;"
                />
            </a>
        <?php endif; ?>
    </div>
</div>

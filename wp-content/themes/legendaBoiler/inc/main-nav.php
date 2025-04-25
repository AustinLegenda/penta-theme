<!--
Navigation Menu
-->
<!--logotype-->
<a href="<?php echo home_url(); ?>" class="grid-top grid-left mobile-nav logo-type targets" id="LogoType">
          <?php
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            if (has_custom_logo()) {
                echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '">';
            } else {
                echo get_bloginfo('name'); 
            }
            ?>
        </a>
        <!--nav-menu-->
     
        
        <?php wp_nav_menu( array(
  'menu'                 => '',
  'container'            => 'nav',
  'container_class'      => 'mobile-nav targets',
  'container_id'         => 'mainNav',
  'container_aria_label' => '',
  'menu_class'           => 'nav-items',
  'menu_id'              => '',
  'echo'                 => true,
  'fallback_cb'          => 'wp_page_menu',
  'before'               => '',
  'after'                => '',
  'link_before'          => '',
  'link_after'           => '',
  'items_wrap'           => '<ul id="%1$s" class="%2$s">%3$s</ul>',
  'item_spacing'         => 'preserve',
  'depth'                => 0,
  'walker'               => new Legenda_Nav_Walker(),
  'theme_location'       => 'mainNav',
) );
          ?>
        <div class="nav-control-btns grid-top grid-right mobile-nav hide targets">
          <h6>=</h6>
        </div>
        <div class="nav-control-btns grid-top grid-right mobile-nav hide">
          <h6>x</h6>
        </div>
      
 
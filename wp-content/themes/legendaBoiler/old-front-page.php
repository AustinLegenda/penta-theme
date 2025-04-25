<?php get_header(); ?>
<?php
$x = 5; //how many posts you want to show in the first section//
$folio = new WP_Query(array(
  'post_type' => array('post', 'folio'),
  'cat' => '8', 
  'order' => 'DSC'
));
?>
<div id="work"></div>
 <div class="folio-wrapper ">
   <div class="folio-container nav-toggle">
      <?php
     /* Start the Loop */
     while ( $folio->have_posts() ) :
      $x++;
        $folio->the_post(); ?>
      <div class="item-folio">
        <?php
        require('inc/snippet-folio.php'); ?>
     </div>
     <?php if($x % 5 == 0){ ?>
    </div>
    
    <div class="folio-container nav-toggle">
      <?php } endwhile; ?>
    </div>
  
  </div>
  <?php get_footer(); ?>
</div> <!--end wrapper-->
</body>
</html>
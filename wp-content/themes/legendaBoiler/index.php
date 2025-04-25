<?php get_header('single'); ?>
<article class= "post-container main-grid-full">
<?php 
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();?> 
	 <?php require('inc/snippet-post.php'); ?>
	<?php } 
} ?>
</article>
</div>
  <?php get_footer(); ?>
  
</body>

</html>
<footer>
  <div class="main-grid">
    <div class="white grid-top grid-left footer-widget" id="FirstFooterWidget">
      <!-- left footer widget "contact info" -->
      <?php dynamic_sidebar('first-footer-widget-area'); ?>
    </div>
    <div class="grid-top grid-right footer-widget" id="SecondFooterWidget">
      <!-- right footer widget "social icons" -->
      <?php dynamic_sidebar('second-footer-widget-area'); ?>
    </div>
  </div>

</footer>
<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
<script>
  window.ga = function() {
    ga.q.push(arguments)
  };
  ga.q = [];
  ga.l = +new Date;
  ga('create', 'UA-XXXXX-Y', 'auto');
  ga('set', 'anonymizeIp', true);
  ga('set', 'transport', 'beacon');
  ga('send', 'pageview')
</script>
<script src="https://www.google-analytics.com/analytics.js" async></script>
<?php wp_footer(); ?>
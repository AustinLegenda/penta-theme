(function ($) {
    $(document).ready(function() {
      $(document).on('click', '.cat-menu-item', function(e) {
        e.preventDefault(); //stop link from going to cat-archieve page. 
  
        var category = $(this).data('category');
        $.ajax({
          url: wpAjax.ajaxUrl,
          data: {
            action: 'filter',
            category: category
          },
          type: 'post',
          success: function (result) {
            $('.post-container').html(result);
          },
          error: function (result) {
            console.log(result);
          }
  
        });
  
      });
  
    });
  })(jQuery);
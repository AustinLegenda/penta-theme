(function ($) {
  $(document).ready(function() {
    $(document).on('click', '.ppg-menu-item', function(e) {
      e.preventDefault(); //stop link from going to cat-archieve page. 

      var category = $(this).data('category');
      $.ajax({
        url: ppgAjax.ajaxUrl,
        data: {
          action: 'filter_folio',
          category: category
        },
        type: 'post',
        success: function (result) {
         

          $('.ppg-container').html(result);
          initFolioLayout();
        },
        error: function (result) {
          console.log(result);
          console.log(ppgAjax);
        }

      });

    });

  });
})(jQuery);
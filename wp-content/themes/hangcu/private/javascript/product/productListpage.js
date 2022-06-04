const $ = jQuery;
module.exports = {
    init: function () {
  
      $('body').on('change', 'input[name="input-orderby"]', function () {
        $('select#orderby').val($(this).val()).trigger('change');
      });
  
      $(window).load(function () {
        var content = $('.woocommerce-ordering').html();
        $('.woocommerce-ordering').html(content);
      });


      this.viewMoveFilter();
    },
    viewMoveFilter : function() {
      $('.viewmore-filter').on('click', function(e) {
        e.preventDefault();
        if( $(this).attr('showed') && $(this).attr('showed') === 'true' ) {
          $(this).html($(this).attr('data-title') + '<i class="icon-down"></i>');
          $(this).closest('ul').css({height: $(this).attr('data-height') + 'px'});
          $(this).attr('showed', false);
        } else {
          $(this).closest('ul').css({height: 'auto'});
          $(this).html('Thu gọn <i class="icon-up"></i>');
          $(this).attr('showed', true);
        }
      })
    }
  };
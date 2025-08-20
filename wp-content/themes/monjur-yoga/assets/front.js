
(function($){
  $(document).on('click','.tab',function(){
    $('.tab').removeClass('active'); $(this).addClass('active');
    // Just a demo: reload a product shortcode via AJAX would be ideal.
    // Here we simply toggle a class or show a notice.
    $('#tab-products').prepend('<p class="notice">Filtered: '+$(this).data('filter')+'</p>');
  });
})(jQuery);

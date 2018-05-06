$(window).scroll(function() {

    if ($(this).scrollTop()>0) {
        $('.hideable-header').fadeOut();
     }
    else {
      $('.hideable-header').fadeIn();
     }
     
    if ($(this).scrollBottom()>0) {
       $('.hideable-footer').fadeOut();
    }
   else {
     $('.hideable-footer').fadeIn();
    }
 });
$(window).scroll(function() {

    if ($(this).scrollTop()>0) {
        $('.hideable-header').fadeOut();
     }
    else {
      $('.hideable-header').fadeIn();
     }
     
    // var scrollBottom = $(document).height() - $(window).height() - $(window).scrollTop();

    if ($(document).height() - $(window).height() - $(window).scrollTop()>0) {
       $('.hideable-footer').fadeOut();
    }
   else {
     $('.hideable-footer').fadeIn();
    }
 });

 $(document).ready(function() {
    if ($(document).height() - $(window).height() - $(window).scrollTop()>0) {
        $('.hideable-footer').fadeOut();
     }
 });
 
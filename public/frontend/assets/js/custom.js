(function ($) {
    "use strict"; // Start of use strict

// Preloader Start
  $(window).on('load',function () {
    $('#preloaderInner').fadeOut();
    $('#preloader')
      .delay(350)
      .fadeOut('slow');
    $('body')
      .delay(350)
  });
// Preloader End

    /*---------------------------------
    Customer Testimonial JS
   -----------------------------------*/
     $('.customer-testimonial-slider').owlCarousel({
      items: 2,
      loop: true,
      autoplay: false,
      autoplayTimeout: 1500,
      margin: 25,
      nav: false,
      dots: true,
      navText: [
        "<span class=\"iconify\" data-icon=\"bi:arrow-left\"></span>",
        "<span class=\"iconify\" data-icon=\"bi:arrow-right\"></span>",
    ],
      smartSpeed: 3000,
      autoplayTimeout:3000,
      responsive:{
        0:{
            items:1
        },
        575:{
            items:1
        },
        991:{
            items:1
        },
        992:{
            items:2
        },
        1199:{
          items:2
        },
        1200:{
          items:2
        }
      }
  });

    var swiper = new Swiper(".ldTestiItems", {
        slidesPerView: 1,
        spaceBetween: 15,
        roundLengths: true,
        loop: false,
        autoplay: true,
        initialSlide: 2,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            992: {
                slidesPerView: 2,
                spaceBetween: 24,
            },
        },
    });
    $("[data-background]").each(function () {
        $(this).css("background-image", "url(" + $(this).attr("data-background") + ")");
    });
})(jQuery); // End of use strict

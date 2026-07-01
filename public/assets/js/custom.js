!(function (n) {
    "use strict";

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
    Top Menu / Side Menu JS
  -----------------------------------*/
    function s() {
        for (
            var e = document
                .getElementById("topnav-menu-content")
                .getElementsByTagName("a"),
            t = 0,
            n = e.length;
            t < n;
            t++
        )
            "nav-item dropdown active" === e[t].parentElement.getAttribute("class") &&
                (e[t].parentElement.classList.remove("active"),
                    e[t].nextElementSibling.classList.remove("show"));
    };

    var a;
    n("#side-menu").metisMenu(),
        n("#vertical-menu-btn").on("click", function (e) {
            e.preventDefault(),
                n("body").toggleClass("sidebar-enable"),
                992 <= n(window).width()
                    ? n("body").toggleClass("vertical-collpsed")
                    : n("body").removeClass("vertical-collpsed");
        }),
        n("body,html").on("click",function (e) {
            var t = n("#vertical-menu-btn");
            t.is(e.target) ||
                0 !== t.has(e.target).length ||
                e.target.closest("div.vertical-menu") ||
                n("body").removeClass("sidebar-enable");
        }),
        n("#sidebar-menu a").each(function () {
            $('#sidebar-menu ul li a').removeClass('mm-active');
            var e = window.location.href.split(/[?#]/)[0];
            this.href == e &&
                (n(this).addClass("active"),
                    n(this).parent().addClass("mm-active"),
                    n(this).parent().parent().addClass("mm-show"),
                    n(this).parent().parent().prev().addClass("mm-active"),
                    n(this).parent().parent().parent().addClass("mm-active"),
                    n(this).parent().parent().parent().parent().addClass("mm-show"),
                    n(this)
                        .parent()
                        .parent()
                        .parent()
                        .parent()
                        .parent()
                        .addClass("mm-active"));
        }),
        n(".navbar-nav a").each(function () {
            var e = window.location.href.split(/[?#]/)[0];
            this.href == e &&
                (n(this).addClass("active"),
                    n(this).parent().addClass("active"),
                    n(this).parent().parent().addClass("active"),
                    n(this).parent().parent().parent().addClass("active"),
                    n(this).parent().parent().parent().parent().addClass("active"),
                    n(this)
                        .parent()
                        .parent()
                        .parent()
                        .parent()
                        .parent()
                        .addClass("active"));
        }),
        n(document).ready(function () {
            var e;
            0 < n("#sidebar-menu").length &&
                0 < n("#sidebar-menu .mm-active .active").length &&
                300 < (e = n("#sidebar-menu .mm-active .active").offset().top) &&
                ((e -= 300),
                    n(".vertical-menu .simplebar-content-wrapper").animate(
                        { scrollTop: e },
                        "slow"
                    ));
        });
/*---------------------------------
  Top Menu / Side Menu JS
-----------------------------------*/

  /*---------------------------------
    Show/Hide Password/ Toggle Password JS
  -----------------------------------*/
    $(".toggle").on("click", function () {

        if ($(".password").attr("type") == "password")
        {
            //Change type attribute
            $(".password").attr("type", "text");
            $(this).removeClass("fa-eye");
            $(this).addClass("fa-eye-slash");
        } else
        {
            //Change type attribute
            $(".password").attr("type", "password");
            $(this).addClass("fa-eye");
            $(this).removeClass("fa-eye-slash");
        }
    });
  /*---------------------------------
    Show/Hide Password/ Toggle Password JS
  -----------------------------------*/

  /*---------------------------------
    Recent Work Slider Js
  -----------------------------------*/
   let slider = $('.gallery-slider-carousel');
   slider.each(function () {
     $(this).owlCarousel({
       nav: true,
       loop:false,
       dots: false,
       navText: [
        "<i class='ri-arrow-left-line'></i>",
        "<i class='ri-arrow-right-line'></i>",
      ],
       margin: 25,
       autoHeight: false,
       stagePadding: 50,

       smartSpeed: 3000,
       autoplayTimeout:3000,

       responsive:{
         0:{
           items: 1,
           stagePadding: 0,
         },
         767:{
           items: 2,
           stagePadding: 25,
         },
         1024:{
           items: 3,
         },
         1200:{
           items: 3,
         },
         1399:{
           items: 5,
         }
       }
     });
   });
    /*---------------------------------
    Recent Work Slider Js
   -----------------------------------*/

    /*---------------------------------
    venobox
   -----------------------------------*/
   $('.venobox').venobox();
    /*---------------------------------
    venobox
   -----------------------------------*/

    /*---------------------------------
    Add Property Stepper Form JS Start
   -----------------------------------*/
  var current_fs, next_fs, previous_fs; //fieldsets
  var opacity;
  var current = 1;
  var steps = $("fieldset").length;

  setProgressBar(current);

  $(".next").on("click",function () {
    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //Add Class Active
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate(
      { opacity: 0 },
      {
        step: function (now) {
          // for making fielset appear animation
          opacity = 1 - now;

          current_fs.css({
            display: "none",
            position: "relative"
          });
          next_fs.css({ opacity: opacity });
        },
        duration: 500
      }
    );
    setProgressBar(++current);
  });

  $(".previous").on("click",function () {
    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    //Remove class active
    $("#progressbar li")
      .eq($("fieldset").index(current_fs))
      .removeClass("active");

    //show the previous fieldset
    previous_fs.show();

    //hide the current fieldset with style
    current_fs.animate(
      { opacity: 0 },
      {
        step: function (now) {
          // for making fielset appear animation
          opacity = 1 - now;

          current_fs.css({
            display: "none",
            position: "relative"
          });
          previous_fs.css({ opacity: opacity });
        },
        duration: 500
      }
    );
    setProgressBar(--current);
  });

  function setProgressBar(curStep) {
    var percent = parseFloat(100 / steps) * curStep;
    percent = percent.toFixed();
    $(".progress-bar").css("width", percent + "%");
  }

  $(".submit").on("click",function () {
    return false;
  });

  /*---------------------------------
    Add Property Stepper Form JS End
   -----------------------------------*/

  /*---------------------------------
    Custom Datepicker JS Start
   -----------------------------------*/
  $(".datepicker").datepicker({
		dateFormat: "yy-mm-dd",
    duration: "fast"
	});
  /*---------------------------------
    Custom Datepicker JS End
  -----------------------------------*/

  /*---------------------------------
    Add Multiple Field Box JS Start
  -----------------------------------*/
  $(".multi-field-wrapper").each(function () {
    var $wrapper = $(".multi-fields", this);
    // $(".add-field", $(this)).click(function (e) {
    //   $(".multi-field:first-child", $wrapper)
    //     .clone(true)
    //     .appendTo($wrapper)
    //     .find("input")
    // });
    // $(".multi-field .remove-field", $wrapper).click(function () {
    //   if ($(".multi-field", $wrapper).length > 1)
    //     $(this).parent(".multi-field").remove();
    // });
  });
  /*---------------------------------
    Add Multiple Field Box JS End
  -----------------------------------*/

// -----------------------------
// Modal in Modal Start
// -----------------------------

// Expences Page Modals
$("#addNewExpenceTypeModal").on('show.bs.modal', function (e) {
  $("#addExpencesModal").modal("hide");
});

$("#addExpencesModal").on('show.bs.modal', function (e) {
  $("#addNewExpenceTypeModal").modal("hide");
});

// Billing Center Page - Create Invoice Modals
$("#createNewInvoiceModal").on('show.bs.modal', function (e) {
  $("#invoicePreviewModal").modal("hide");
});

// -----------------------------
// Modal in Modal End
// -----------------------------

/*---------------------------------
account-page- dropdown menu
-----------------------------------*/
  $( ".menu-has-children" ).on("click", function() {
    $(this).toggleClass( "has-open" );
  });
/*---------------------------------
account-page- dropdown menu
-----------------------------------*/

/*------------------------
notice button coles
 -----------------------*/



const notice = $("#notice").length
if(notice){
  $(".page-content").css('padding','calc(115px + 0px) calc(24px / 2) 0 calc(24px / 2)')
  $(".vertical-menu").css('top','135px')
}else{
  $(".page-content").css('padding','calc(80px + 0px) calc(24px / 2) 0 calc(24px / 2)')
  $(".vertical-menu").css('top','100px')
}

$(".topBannerClose").on("click", function() {
  $(".page-content").css('padding','calc(80px + 0px) calc(24px / 2) 0 calc(24px / 2)')
  $(".vertical-menu").css('top','100px')
});

/*------------------------
notice button coles
 -----------------------*/

 $(".exclamation-btu").on("click", function() {
  $(".exclamation-area").css('display','inline-block')
  $(".exclamation").addClass('clickOption')
  $(".topBannerClose").css('display','inline-block')
  $(".exclamation-btu").css('display','none')

});

// -----------------------------
// price in click show text stat
// -----------------------------

    $("#zPrice-plan-switch").on("click", function () {
        $(".zPrice-plan-monthly").toggleClass("d-none");
        $(".zPrice-plan-yearly").toggleClass("d-none");
    });

// -----------------------------
// price in click show text end
// -----------------------------

// -----------------------------
// testimonial in click show text stat
// -----------------------------

    var swiperTesti = new Swiper(".ldTestiItems", {
        slidesPerView: 1,
        spaceBetween: 15,
        roundLengths: true,
        loop: true,
        autoplay: true,
        initialSlide: 2,
        pagination: {
            el: ".swiper-pagination",
        },
        breakpoints: {
            992: {
                slidesPerView: 2,
                spaceBetween: 24,
            },
            // 768: {
            //   slidesPerView: 4,
            //   spaceBetween: 40,
            // },
            // 1024: {
            //   slidesPerView: 5,
            //   spaceBetween: 50,
            // },
        },
    });

// -----------------------------
// testimonial in click show text end
// -----------------------------


// -----------------------------
// exclamation in click show text stat
// -----------------------------



// -----------------------------
// exclamation in click show text end
// -----------------------------

})(jQuery);

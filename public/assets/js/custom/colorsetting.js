$("#colorpickerbody").hide();
$("#lease-property-tab").on("click",function(){
    $("#colorpickerbody").show();
    $("#websiteColorMode").val(1);
});

$("#own-property-tab").on("click",function(){
    $("#colorpickerbody").hide();
    $("#websiteColorMode").val(0);
});

var colormode =  $("#websiteColorMode").val();

if ((colormode == 0) || (colormode == '') ) {
    $("#own-property-tab").addClass("active");
    $("#colorpickerbody").hide();
}

if ((colormode == 1)  ) {
    $("#lease-property-tab").addClass("active");
    $("#colorpickerbody").show();
}


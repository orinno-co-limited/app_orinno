(function ($) {
    "use strict";

    $(".property-search").on("input property-search",function(){
        var getSearchRoute = $('#getSearchRoute').val();
        commonAjax('GET', getSearchRoute, getSearchValue, getSearchValue, { 'search': this.value });
     });

     function getSearchValue(response) {
        // console.log(response);
     }

})(jQuery)

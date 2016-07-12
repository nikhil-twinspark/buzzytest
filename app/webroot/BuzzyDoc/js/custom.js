// jQuery by Skant

/************************************** common dropdown function **************************************/
(function($){
  $.fn.dropdown = function(options) {
    var defaults = $.extend({
    selectedItem:"selectedItem",
    selectDropdown:"selectDropdown"
    }, options);

  return this.each(function() {
    var itemValue = $(options.selectDropdown).find('option:selected').text();
    $(options.selectedItem).text(itemValue);
    $(options.selectDropdown).change(function(){
      var itemValue = $(this).find('option:selected').text();
      $(options.selectedItem).text(itemValue);
    });
  });
};

})(jQuery);
/************************************** common dropdown function End **************************************/


/************************************** Home page carousel **************************************/
$(function(){
  $("#owl-demo").owlCarousel({
    autoPlay : 2000,
    stopOnHover : true,
    navigation:false,
    paginationSpeed : 1000,
    goToFirstSpeed : 2000,
    singleItem : true,
    autoHeight : true
  });
});
/************************************** End Home page carousel **************************************/


/************************************** Main top menu toggle **************************************/
(function($){
  $('.menu-btn').on('click', function(){
    $('.top-nav').slideToggle();
  })

  $(".top-nav").on('click', function(e){
    e.stopPropagation();
  });

  $(document).click(function (e){
    var container = $(".menu-btn"),
        windowWidth = $(window).width();

    if(windowWidth < 767){
      if (!container.is(e.target) // if the target of the click isn't the container...
          && container.has(e.target).length === 0) // ... nor a descendant of the container
      {
        $(".top-nav").slideUp();
      }      // Do stuff here
    }
  });

})(jQuery);
/************************************** End Main top menu toggle **************************************/





$(document).ready(function(){
  $(".doctor-dropdown").dropdown({selectedItem:".doctor-dropdown-value", selectDropdown:".listing"});





}); // main document ready function closed.

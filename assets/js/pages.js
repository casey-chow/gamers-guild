$(function() {

  function page_name ($page) {
    return $page.attr('id').split('-')[1];//Expected: #page-home
  }

  function move_navigation($page) {
    var $nav = $('#navigation');
    var yoffset = $page.offset().top;

    console.log('Moving nav to', page_name($page));

    $nav
      .removeClass() //remove all classes
      .addClass('is-' + page_name($page))
      .animate({ top: yoffset }, 'fast'); //pull the nav down
  }

  function scroll_to(position, duration, easing, callback) {
    position = (position === 'top') ? 0 : position;
    $('html,body').animate({ scrollTop: position }, 'slow', easing, callback);
  }

  $.waypoints.settings.scrollThrottle = 15;


  /* Moving the Navigation Between Pages
   * ---------------------------------------------- */

  var old_hash;

  $('.page-section').waypoint(function(evt, dir) {
    var $page = $(this);
    move_navigation($page);
  }, { 
    offset: function() {
      //var winheight = $.waypoints('viewportHeight');
      //FIXME doesn't work right in the up direction
      if ( $(this).is('#page-home') ) { return -25; }
      else if ( location.hash !== old_hash ) { //is a hashchange
        old_hash = location.hash;
        return -1;
      } else { return 10; }
    }
  });

  if (!location.hash) { $('#navigation').addClass('is-home'); }
  else { move_navigation($(location.hash)); }
});

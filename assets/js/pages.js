$(function() {

  var last_location;

  /* Function Definitions
   * ----------------------------------------------- */

  function page_name ($page) {
    return $page.attr('id').split('-')[1]; //Expected: #page-home
  }

  function move_navigation($page) {
    var $nav = $('#navigation');
    var yoffset = $page.offset().top;

    console.log('Moving nav to', page_name($page));

    $nav
      .removeClass() // remove all classes
      .addClass('is-' + page_name($page))
      .animate({ top: yoffset }, 200); // pull the nav down

    last_location = page_name($page);
  }

  function scroll_to(position, duration, easing, callback) {
    if ( position.jquery ) { position = position.offset().top + 1; }
    if ( position === 'top' ) { position = 0; }

    $('html,body').animate({ scrollTop: position }, 'slow', easing, callback);
    // TODO: Change hash on scroll
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
      //FIXME doesn't work right in the up direction
      if ( $(this).is('#page-home') ) { return -25; }
      else if ( location.hash !== old_hash ) { //is a hashchange
        old_hash = location.hash;
        return -1;
      } else { return 10; }
    }
  });

  // Catch it if a hash change doesn't move the navigation
  $(window).hashChange(function() {
    var page_name = location.hash.slice(1);
    setTimeout(function() {
      if (last_location !== page_name) {
        move_navigation($('#' + page_name));
      }
    }, 201);
  });

  /* Moving between Pages
   * ----------------------------------------- */

  // by keys
  $(document).keydown(function(evt) {
    if (evt.keyCode < 37 && evt.keyCode > 40) { return; }

    var $win = $(window);
    var viewport_top = $win.scrollTop();
    var viewport_bot = viewport_top + $win.height();
    var $current, $target;

    // find first page in viewport
    // theoretically, only one top should be in the viewport at a time
    $('.page-section').each(function() {
      var $this = $(this);
      var offset = $this.offset().top;
      if ( offset >= viewport_top && offset < viewport_bot ) {
        $current = $this;
      }
    });

    switch(evt.keyCode) {
      case 37: // left
      case 38: // up
        $target = $current.prev();
        break;
      case 39: // right
      case 40: // down
        $target = $current.next();
        break;
    }

    if (!$target) { return; }

    // scroll to the page identified
    scroll_to($target);
    setTimeout(function() {
      if (last_location !== page_name($target)) {
        move_navigation($('#page-' + page_name($target)));
      }
    }, 401);
  });

  /* Changing Hash on Scroll
   * ------------------------------------------- */

  // TODO: function that changes the hash on scroll

  // If there's no hash, assume it should be at home
  if (!location.hash) { $('#navigation').addClass('is-home'); }
  else { move_navigation($(location.hash)); }

});

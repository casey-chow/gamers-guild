$(function() {

  var last_location;

  /* Function Definitions
   * ----------------------------------------------- */
  function is_between (num, btm, top, tolerance) {
    var above_lower = num >= (btm - tolerance);
    var below_upper = num <= (top + tolerance);
    return above_lower && below_upper;
  }

  function page_name ($page) {
    return $page.attr('id').split('-')[1]; //Expected: #page-home
  }

  function move_navigation($page) {
    var $nav = $('#navigation');
    var yoffset = $page.offset().top;
    var _page_name = page_name($page);

    $nav
      .removeClass() // remove all classes
      .addClass('is-' + _page_name)
      .animate({ top: yoffset }, 200) // pull the nav down
      // put the is-active class on the right thing
      .find('a.is-active')
        .removeClass('is-active')
        .end()
      .find('[href=#page-' + _page_name + ']')
        .addClass('is-active')
        .end()


    last_location = _page_name;
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
    if (dir === 'down') {
      move_navigation($(this));
    }
  }, { 
    offset: '12.5%'
  });

  // Bind a waypoint to the inner so that it works upwards too
  // https://github.com/imakewebthings/jquery-waypoints/issues/31
  $('.page-section-inner').waypoint(function(evt, dir) {
    if (dir === 'up') {
      move_navigation($(this).parent());
    }
  }, {
    offset: '-12.5%'
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
    var $current, $target;

    var win_top = $win.scrollTop();
    var win_bot = win_top + $win.height();

    // Find first page in viewport
    $('.page-section').each(function() {
      var offset = $(this).offset().top;
      // if element is in viewport (give or take)
      if ( is_between(offset, win_top, win_bot, 50) ) {
        $current = $(this);
        return false; //short circuit
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

  // If there's no hash, assume it should be at home
  if (!location.hash) { $('#navigation').addClass('is-home'); }
  else { move_navigation($(location.hash)); }

});

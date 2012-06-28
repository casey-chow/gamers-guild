$(function() {
  // As a hackish fix to changing all the ids into classes
  $('.page-section').each(function() {
    var $this = $(this);
    var classes = $this.attr('class').split(' ');
    for (var i = 0; i < classes.length; i += 1) {
      if (classes[i] !== 'page-section' 
         && classes[i].indexOf('page-') !== -1) {
        $this.attr('id', classes[i]);
      }
    }
  });

  $('.menu-item-link').click(function(evt) {
    var $this = $(this);
    var $src = $(evt.target);
    var url = $src.attr('href');
    var tokens = url.split('/'); 
    var target = tokens[tokens.length - 2];

    target = target == 'wordpress' ? 'home' : target;
    location.replace('#page-' + target);
    return false;
  });

  // Another hackish fix to latch onto the navigation items

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
    var $nav = $('#header');
    var yoffset = $page.offset().top;
    var _page_name = page_name($page);

    $nav
      .removeClass() // remove all classes
      .addClass('page-' + _page_name)
      .animate({ top: yoffset }, 200) // pull the nav down
      .find('.current-menu-item')
        .removeClass('current-menu-item')
        .end()
      .find('[href$="' + _page_name + '/"]')
        .parent()
        .addClass('current-menu-item')
        .end()

    if (window.history.pushState) {
      history.pushState(null, null, '#page-' + _page_name);
    }
  }

  function scroll_to(position, duration, easing, callback) {
    if ( position.jquery ) { position = position.offset().top + 1; }
    if ( position === 'top' ) { position = 0; }

    $('html,body').animate({ scrollTop: position }, 'slow', easing, callback);
  }

  $.waypoints.settings.scrollThrottle = 15;

  /* Changing Hash on Scroll
   * ------------------------------------------- */

  // If there's no hash, assume it should be at home
  if (!location.hash && $(window).scrollTop() === 0) { 
    $('#header').addClass('page-home'); 
  } else {
    move_navigation($(location.hash));
    scroll_to($(location.hash));
  }


  /* Moving the Navigation Between Pages
   * ---------------------------------------------- */

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

  /* Moving between Pages
   * ----------------------------------------- */

  // by keys
  $(document).keydown(function(evt) {
    var key = evt.keyCode;
    if (key < 37 && key > 40 || key < 74 && key > 75) { return; }

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
      case 75: // k
        $target = $current.prev();
        break;
      case 39: // right
      case 40: // down
      case 74: // j
        $target = $current.next();
        break;
    }

    if (!$target) { return; }

    scroll_to($target);
  });

});

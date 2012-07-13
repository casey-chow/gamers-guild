// Loads only on front page 

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
        .into(function(nav_item) {
          // latch it into home since it'll lead to the base path, not /home/
          if (_page_name === 'home') {
            return $('[href="' + base_path + '"]');
          } else {
            return nav_item;
          }
        })
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

  var base_path = $('.header-title').attr('href');

  /* Latch onto the Navigation to Scroll
   * ----------------------------------------- */

  $('.menu-item-link').click(function(evt) {
    var url = $(evt.target).attr('href');
    var tokens = url.split('/'); 
    var target = tokens[tokens.length - 2]; // assuming trailing slash

    target = url === base_path ? 'home' : target;
    location.replace('#page-' + target);
    return false;
  });


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


  /* Ajax Loading for the Info Page
   * ---------------------------------------------- */
  $('.page-info .page_item a').click(function() {
    var $this = $(this);
    var $pageinfo = $('.page-info');
    var url = $this.attr('href');
    var content;

    $pageinfo
      .find('.text-article')
      .load(url + ' .text-article', function() {
        //now replace that with the child .text-article
        $(this)
          .find('.text-article')
          .unwrap()
          .find(find)

        rejig_section_selectors();
      });

    $('.page-section.page-info .page_item a')
      .css('color', '#06C');
    $(this).css('color', '#C00');


    return false;
  });

});

/*
The MIT License

Copyright (c) 2010 Reginald Braithwaite http://reginald.braythwayt.com
and Ben Alman http://benalman.com/

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

(function($,undefined){
	
	var jq_fn = $.fn,
		aps = Array.prototype.slice,
		noop = function () {};
	
	// combinators
	
	jq_fn.T = function( fn ) {
		fn = typeof Functional != 'undefined' ? Functional.lambda( fn ) : fn;
		return fn.apply( this, [this].concat(aps.call( arguments, 1 )) );
	};
	
	jq_fn.K = function( fn ) {
		fn = typeof Functional != 'undefined' ? Functional.lambda( fn ) : fn;
		fn.apply( this, [this].concat(aps.call( arguments, 1 )) );
		return this;
	};
	
	// variations
	
	jq_fn.ergo = function( fn, optionalUnless ) {
		var whichFn = this.length ? fn : (optionalUnless ? optionalUnless : noop);
		whichFn = typeof Functional != 'undefined' ? Functional.lambda( whichFn ) : whichFn;
		whichFn.apply( this, [this].concat(aps.call( arguments, 1 )) );
		return this;
	};
	
	jq_fn.when = function (fn) {
		fn = typeof Functional != 'undefined' ? Functional.lambda( fn ) : fn;
		return fn.apply( this, [this].concat(aps.call( arguments, 1 )) ) ? this.filter('*') : this.filter('not(*)');
	};
	
	// aliases
	
	if ( jq_fn.tap === undefined ) {
		jq_fn.tap = jq_fn.K;
	}
	
	if ( jq_fn.into === undefined ) {
		jq_fn.into = jq_fn.T;
	}
	
})(jQuery);

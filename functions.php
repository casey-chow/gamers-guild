<?php
        // Translations can be filed in the /languages/ directory
        load_theme_textdomain( 'html5reset', TEMPLATEPATH . '/languages' );
 
        $locale = get_locale();
        $locale_file = TEMPLATEPATH . "/languages/$locale.php";
        if ( is_readable($locale_file) )
            require_once($locale_file);
	
	// Add RSS links to <head> section
	automatic_feed_links();
	
	// Load jQuery
	if ( !function_exists(core_mods) ) {
		function core_mods() {
			if ( !is_admin() ) {
				wp_deregister_script('jquery');
				wp_register_script('jquery', ("//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"), false);
				wp_enqueue_script('jquery');
			}
		}
		core_mods();
	}

	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
  if (function_exists('register_sidebar')) {
    register_sidebar(array(
      'name' => __('Sidebar Widgets','html5reset' ),
      'id'   => 'sidebar-widgets',
      'description'   => __( 'These are widgets for the sidebar.','html5reset' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2>',
      'after_title'   => '</h2>'
    ));
  }
  
	register_nav_menus(
		array( 
			'main_nav' => 'Main Menu'
		)
	);

  class walker extends Walker_Nav_Menu {
    // TODO: is this necessary?
    function start_el(&$output, $item, $depth, $args) {
      global $wp_query;
      $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

      $class_names = $value = '';

      $classes = empty( $item->classes ) ? array() : (array) $item->classes;

      $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
      $class_names = ' class="' . esc_attr( $class_names ) . '"';

      $output .= $indent . '<li' . $value . $class_names .'>';

      $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
      $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
      $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
      $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

      $item_output = $args->before;
      $item_output .= '<a'. $attributes .'>';
      $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
      $item_output .= '<br /><span class="sub">' . $item->description . '</span>';
      $item_output .= '</a>';
      $item_output .= $args->after;

      $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
  }

  function main_nav() {
    wp_nav_menu(array(
      'menu'            => 'main_nav', /* menu name */
      'theme_location'  => 'main_nav', /* where in the theme it's assigned */
      'container'       => 'nav',
      'container_class' => 'navigation-menu'
      'menu_class'      => 'navigation-list',
      'walker'          => new walker /* customizes the output of the menu */
    ));
  }

  add_theme_support( 'post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'audio', 'chat', 'video')); // Add 3.1 post format theme support.

  function sendUaCompatible() {
      // https://github.com/h5bp/html5-boilerplate/issues/378
      header("X-UA-Compatible: IE=edge,chrome=1");
    }
    add_action('send_headers', 'sendUaCompatible');

?>

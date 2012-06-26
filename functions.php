<?php
  // Includes
  require_once('_/inc/headjs-loader.php');

	// Load jQuery
  function loadjQuery() {
      if ( !is_admin() ) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.7.4/jquery.min.js"), false);
        wp_enqueue_script('jquery');
      }
    }
    add_action('wp_enqueue_scripts', 'loadjQuery');

	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');

  
  function theme_support() {
      add_theme_support('automatic-feed-links');

      add_theme_support('menus');
      register_nav_menus(array( 
        'main_nav'  => 'Main Menu'
      ));
    }
    add_action('after_setup_theme','theme_support');	

  class custom_walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth, $args) {
      global $wp_query;

      $class_names = $value = '';

      $classes = empty( $item->classes ) ? array() : (array) $item->classes;

      $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
      $class_names = ' class="' . esc_attr( $class_names ) . '"';

      $output .= "\n<li" . $value . $class_names .'>';

      $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
      $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
      $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
      $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
      $attributes .= ! empty( $args->href_class ) ? ' class="'   . esc_attr( $args->href_class ) .'"' : '';

      $item_output = $args->before;
      $item_output .= '<a'. $attributes .'>';
      $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
      $item_output .= '</a>';
      $item_output .= $args->after;

      $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
  }

  function main_nav() {
    wp_nav_menu(array(
      'menu'            => 'main_nav',
      'theme_location'  => 'main_nav',
      'depth'           =>  1, // TODO: submenus
      'container'       => 'nav',
      'container_class' => 'header-navigation',
      'href_class'      => 'menu-item-link',
      'menu_class'      => 'navigation-menu h1',
      'walker'          => new custom_walker
    ));
  }

  class home_page_walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth, $args) {
      $output .= get_post_meta( $item->ID, '_menu_item_object_id', true );
    }
    function end_el(&$output, $item, $depth, $args) {
      $output .= ',';
    }
  }

  function home_pages() {
      $page_ids = wp_nav_menu(array(
        'menu'            => 'main_nav',
        'theme_location'  => 'main_nav',
        'depth'           => 1,
        'container'       => false,
        'items_wrap'      => '%3$s',
        'walker'          => new home_page_walker,
        'echo'            => false
      ));

      $page_ids = explode(',', $page_ids);

      foreach ($page_ids as $page_id):
      if(function_exists('iinclude_page')):
        $title = get_the_title($page_id);
        ?>
        <section class="page-section" id="page-<?php the_slug($page_id); ?>">
          <h1 class="section-name"><?php echo $title; ?></h1>
          <div class="page-section-inner group">
            <?php iinclude_page($page_id); ?>
          </div>
        </section>
        <?php
      endif;
      endforeach;
    }

  function the_slug ($page_id) {
      echo get_post($page_id)->post_name;
    }

  function get_parent_ID() {
      global $wp_query;

      if( empty($wp_query->post->post_parent) ) {
        return $wp_query->post->ID;
      } else {
        return $wp_query->post->post_parent;
      }
    }

  function get_section_title() {
      $parent = get_parent_ID();
      return get_the_title($parent);
    }

  function list_child_pages($classnames) {
      $parent = get_parent_ID();
      $output= '';
      $children = wp_list_pages("title_li=&child_of=$parent&depth=1&echo=0");

      if ($children) {
        $output .= "<ul class=\"$classnames\">\n";
        $output .= $children;
        $output .= "</ul>\n";
      }
      echo $output;
    }

  function sendUaCompatible() {
      // https://github.com/h5bp/html5-boilerplate/issues/378
      header("X-UA-Compatible: IE=edge,chrome=1");
    }
    add_action('send_headers', 'sendUaCompatible');

?>

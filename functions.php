<?php
  // Includes
  require_once('_/inc/headjs-loader.php');

	// Load jQuery
  function load_jquery() {
      if ( !is_admin() ) {
        wp_deregister_script('jquery');
        //wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"), false);
        wp_register_script(
          'jquery',
          get_template_directory_uri() . '/_/js/jquery.js'
        );
        //wp_enqueue_script('jquery'); //uncomment to load jQuery always
      }
    }
    add_action('wp_enqueue_scripts', 'load_jquery');

  function load_home_scripts() {
      if (is_front_page()) {
        wp_enqueue_script(
          'waypoints',
          get_template_directory_uri() . '/_/js/waypoints.js',
          array('jquery')
        );
        wp_enqueue_script(
          'front-page-js', 
          get_template_directory_uri() . '/_/js/front-page.js',
          array('jquery', 'waypoints', 'app_js')
        );
      }
    }
    add_action('wp_enqueue_scripts', 'load_home_scripts');

  function load_app_scripts() {
      wp_enqueue_script(
        'app_js',
        get_template_directory_uri() . '/_/js/app.js',
        array('jquery')
      );
    }
    add_action('wp_enqueue_scripts', 'load_app_scripts');

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
      'depth'           =>  1, 
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

  function the_slug ($page_id) {
      echo get_post($page_id)->post_name;
    }

  function get_parent_ID($post = NULL) {
      global $wp_query;
      $post = is_null($post) ? $wp_query->post : get_post($post);

      if( empty($post->post_parent) ) {
        return $post->ID;
      } else {
        return $post->post_parent;
      }
    }

  function get_section_title($page_id = NULL) {
      $parent = get_parent_ID($page_id);
      return get_the_title($parent);
    }

  function list_child_pages($classnames = '', $page_id = NULL) {
      $parent = get_parent_ID($page_id);
      $output= '';
      $children = wp_list_pages("title_li=&child_of=$parent&depth=1&echo=0");

      if ($children) {
        $output .= "<ul class=\"$classnames\">\n";
        $output .= $children;
        $output .= "</ul>\n";
      }
      echo $output;
    }

  function list_archives($classnames) {
      echo "<ul class=\"$classnames\">";
      wp_get_archives(array(
        'limit' => 12
      ));
      echo "</ul>";
    }

  function sendUaCompatible() {
      // https://github.com/h5bp/html5-boilerplate/issues/378
      header("X-UA-Compatible: IE=edge,chrome=1");
    }
    add_action('send_headers', 'sendUaCompatible');


  function load_blog_posts($params) {
      $my_query = new WP_Query($params);
      if ($my_query->have_posts()): while ($my_query->have_posts()) : 
        $my_query->the_post();
      ?>
        <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
          <h2><?php the_title(); ?></h2>
          <?php include (TEMPLATEPATH . '/_/inc/meta.php' ); ?>
          <div class="entry">
            <?php the_excerpt(); ?>
          </div>
            <a href="<?php the_permalink(); ?>" class="follow-through">Read more</a>
        </article>
      <?php
      endwhile; endif;
      wp_reset_query();
    }

?>

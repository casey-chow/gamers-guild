<?php get_header(); ?>

<?php
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
      $page_ids = array_values(array_filter($page_ids)); // remove empty

      foreach ($page_ids as $page_id):
      if (function_exists('iinclude_page')):
        $title = get_the_title($page_id);
        // TODO: Check if page is blog page and load blog posts
        // TODO: Check if page is info page and load template
        ?>
        <section class="page-section page-<?php the_slug($page_id); ?>">
          <h1 class="section-name"><?php echo $title; ?></h1>
          <div class="page-section-inner group">
            <?php $template = get_post_meta($page_id, '_wp_page_template', true);
            if ($template == 'page-section.php'): ?>
              <nav class="section-selector">
                <h1><?php echo get_section_title($page_id); ?></h1>
                <?php //TODO: AJAX page loading ?>
                <?php list_archives('section-selector-items'); ?>
              </nav>
            <?php endif; ?>
            <article class="text-article" id="post-<?php echo $page_id ?>">
              <?php iinclude_page($page_id); ?>
              <?php if (in_category('Blog', $page_id)): ?>
                <?php load_blog_posts(array(
                  'posts_per_page' => 3,
                  'post_type' => 'post'
                )); ?>
              <?php endif; ?>
          </div>
        </section>
        <?php
      endif;
      endforeach;
?>

<?php get_footer(); ?>

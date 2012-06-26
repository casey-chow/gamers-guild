<?php get_header(); ?>
  <section class="page-section">
    <div class="page-section-inner group">
      <nav class="section-selector blog-archives">
        <h1>Blog</h1>
        <?php list_archives('section-selector-items'); ?>
      </nav>
      <div class="text-article">
        <?php if (have_posts()) : ?>
          <h2>
            <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
            <?php if (is_category()) { ?>
              Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category
            <?php } elseif( is_tag() ) { ?>
              Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;
            <?php } elseif (is_day()) { ?>
              Archive for <?php the_time('F jS, Y'); ?>
            <?php } elseif (is_month()) { ?>
              Archive for <?php the_time('F, Y'); ?>
            <?php } elseif (is_year()) { ?>
              Archive for <?php the_time('Y'); ?>
            <?php } elseif (is_author()) { ?>
              Author Archive
            <?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
              Blog Archives
            <?php } ?>
          </h2>

          <?php include (TEMPLATEPATH . '/_/inc/nav.php' ); ?>

          <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class() ?>>
            
                <h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
              
                <?php include (TEMPLATEPATH . '/_/inc/meta.php' ); ?>

                <div class="entry">
                  <?php the_content(); ?>
                </div>

            </article>

          <?php endwhile; ?>

          <?php include (TEMPLATEPATH . '/_/inc/nav.php' ); ?>
          
      <?php else : ?>

        <h2>Nothing found</h2>

      <?php endif; ?>

<?php get_footer(); ?>

<?php // index.php does little more than the blog and posts now
get_header(); ?>
  <section class="page-section">
    <div class="page-section-inner group">
      <nav class="section-selector blog-archives">
        <h1>Blog</h1>
        <?php list_archives('section-selector-items'); ?>
      </nav>
      <div class="text-article">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
            <?php get_template_part('content', 'post'); ?>
          </article>

        <?php endwhile; ?>

        <?php include (TEMPLATEPATH . '/_/inc/nav.php' ); ?>
        <?php else : ?>
          <h2>Not Found</h2>
        <?php endif; ?>
      </div>
    </div>
  </section> <?php  //.page-section ?>

<?php get_footer(); ?>

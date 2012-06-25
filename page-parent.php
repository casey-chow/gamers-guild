<?php
/*
Template Name: Parent
*/
?>

<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <section class="page-section" id="page-<?php the_slug(get_the_ID()); ?>">
    <div class="page-section-inner group">
      <nav class="section-selector" id="info-section-selector">
        <h1>Info</h1>
        <?php //TODO: AJAX page loading ?>
        <?php get_child_pages('section-selector-items h2'); ?>
      </nav>
      <article class="text-article" id="post-<?php the_ID(); ?>">
        <h2><?php the_title(); ?></h2>
        <?php include (TEMPLATEPATH . '/_/inc/meta.php' ); ?>
        <div class="entry">
          <?php the_content(); ?>
          <?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>
        </div>
        <?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
      </article>
    </div>
  </section>

  <?php comments_template(); ?>
  <?php endwhile; endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

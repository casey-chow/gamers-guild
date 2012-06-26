<?php
/*
Template Name: With Section Selector
*/
?>

<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <section class="page-section page-<?php the_slug(); ?>">
    <div class="page-section-inner group">
      <nav class="section-selector">
        <h1><?php echo get_section_title(); ?></h1>
        <?php //TODO: AJAX page loading ?>
        <?php list_child_pages('section-selector-items h2'); ?>
      </nav>
      <article class="text-article" id="post-<?php the_ID(); ?>">
        <?php the_content(); ?>
        <?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
      </article>
    </div>
  </section>

  <?php endwhile; endif; ?>

<?php get_footer(); ?>

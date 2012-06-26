<?php
/*
Template Name: No Title
*/
?>

<?php get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <section class="page-section page-<?php the_slug(); ?>">
    <div class="page-section-inner group">
        <?php the_content(); ?>
        <?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
      </article>
    </div>
  </section>

  <?php endwhile; endif; ?>

<?php get_footer(); ?>

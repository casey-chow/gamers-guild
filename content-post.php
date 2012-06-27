<h1><?php the_title(); ?></h1>
<?php include (TEMPLATEPATH . '/_/inc/meta.php' ); ?>

<div class="entry">
  <?php the_content(); ?>
</div>

<footer class="postmetadata">
  <?php the_tags('Tags: ', ', ', '<br />'); ?>
  Posted in <?php the_category(', ') ?> | 
  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
</footer>

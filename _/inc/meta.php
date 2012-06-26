<footer class="meta">
  Posted on: <a href="<?php the_permalink(); ?>">
    <time datetime="<?php echo date(DATE_W3C); ?>" pubdate class="updated">
      <?php the_time('F jS, Y') ?>
    </time>
  </a>

	<span class="byline author vcard">
		by <span class="fn"><?php the_author() ?></span>
	</span>

	<?php comments_popup_link('No Comments', '1 Comment', '% Comments', 'comments-link', ''); ?>
</footer>

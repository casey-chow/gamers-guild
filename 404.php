<?php get_header(); ?>

  <section class="page-section">
    <div class="page-section-inner group">
      <article class="text-article" id="post-<?php the_ID(); ?>">
        <img class="centered" src="<?php echo bloginfo('template_directory') . "/_/img/hal9000.png"; ?>" alt="" />
        <h1>I'm sorry, Dave. I'm afraid I can't let  you do that.</h1>
        <p>The page you requested does not currently exist. However, with a decent amount of time travel equipment, I am sure you may find the page in the future.</p>

        <p>Please, sit back, relax, and surf the internet.</p>
        
        <?php get_search_form(); ?>
      </article>
    </div>
  </section>

<?php get_footer(); ?>

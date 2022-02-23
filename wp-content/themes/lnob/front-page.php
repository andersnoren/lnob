<?php get_header(); ?>

<main id="site-content" role="main">

	<section class="section-inner pv-40 pv-t-60 pv-d-80">

		<?php while ( have_posts() ) : the_post(); ?>

			

		<?php endwhile; ?>
	
	</section><!-- .section-inner -->

</main><!-- #site-content -->

<?php get_footer(); ?>
<?php 

get_header();

while ( have_posts() ) : the_post(); 
	?>

	<main id="site-content" role="main">

		<?php

		/* ----------------------------------- */
		/* Hero ------------------------------ */
		/* ----------------------------------- */

		$hero = get_field( 'hero' );

		?>

		<section id="section-hero" class="hero">

			<div class="section-inner">



			</div><!-- .section-inner -->

		</section><!-- #section-hero -->

		<?php

		/* ----------------------------------- */
		/* Introduction ---------------------- */
		/* ----------------------------------- */

		$introduction = get_field( 'introduction' );

		?>

		<section id="section-introduction" class="introduction">

			<div class="section-inner">



			</div><!-- .section-inner -->

		</section><!-- #section-introduction -->

		<?php

		/* ----------------------------------- */
		/* Global Goals ---------------------- */
		/* ----------------------------------- */

		?>

		<?php

		/* ----------------------------------- */
		/* Missing In Stats ------------------ */
		/* ----------------------------------- */

		?>

		<section id="section-missing-in-stats" class="missing-in-stats">

			<div class="section-inner">



			</div><!-- .section-inner -->

		</section><!-- #section-missing-in-stats -->

		<?php

		/* ----------------------------------- */
		/* Recommendations ------------------- */
		/* ----------------------------------- */

		?>

		<section id="section-recommendations" class="recommendations">

			<div class="section-inner">



			</div><!-- .section-inner -->

		</section><!-- #section-recommendations -->

	</main><!-- #site-content -->

	<?php

endwhile;

get_footer();

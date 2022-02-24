<?php 

get_header();

while ( have_posts() ) : the_post(); 
	?>

	<main id="site-content" role="main">

		<?php

		/* ----------------------------------- */
		/* Hero ------------------------------ */
		/* ----------------------------------- */

		$hero 				= get_field( 'hero' );
		$title 				= ! empty( $hero['title'] ) ? $hero['title'] : '';
		$intro_text 		= ! empty( $hero['intro_text'] ) ? $hero['intro_text'] : '';
		$scroll_button_text = ! empty( $hero['scroll_button_text'] ) ? $hero['scroll_button_text'] : __( 'Scrolla', 'lnob' );

		?>

		<section id="section-hero" class="hero pv-0">

			<div class="hero-inner section-inner">

				<div class="hero-header-placeholder"></div>

				<header class="page-header mw-small pu-48 pd-64 pu-t-64 pd-t-80">

					<?php if ( $title ) : ?>
						<h1 class="page-title no-margin"><?php echo $title; ?></h1>
					<?php endif; ?>

					<?php if ( $intro_text ) : ?>
						<div class="intro-text fs-intro-text contain-margins pu-24 pu-t-32"><?php echo wpautop( $intro_text ); ?></div>
					<?php endif; ?>

					<div class="social-wrapper pu-24 pu-t-32">
						<?php lnob_the_share_links(); ?>
					</div><!-- .social-wrapper -->
				
				</header><!-- .page-header -->

				<div class="scroll-button-wrapper">
					<?php 
					echo lnob_get_squircle_link( array(
						'icon'	=> array(
							'name'	=> 'arrow-down',
						),
						'text'	=> $scroll_button_text,
						'url'	=> '#section-introduction',
					) );
					?>
				</div>

			</div><!-- .hero-inner -->

			<div class="lnob-symbol spot-fade-scale-up">
				<div class="lnob-symbol-inner">
					<?php lnob_the_svg( 'logos/lnob-symbol' ); ?>
				</div>
			</div>

		</section><!-- #section-hero -->

		<?php

		/* ----------------------------------- */
		/* Introduction ---------------------- */
		/* ----------------------------------- */

		$introduction 		= get_field( 'introduction' );
		$content_post_obj 	= ! empty( $introduction['content_post_object'] ) ? $introduction['content_post_object'] : false;
		$scroll_button_text = ! empty( $introduction['scroll_button_text'] ) ? $introduction['scroll_button_text'] : __( 'Scrolla', 'lnob' );

		$rec_shortcut 				= ! empty( $introduction['recommendations_box'] ) ? $introduction['recommendations_box'] : false;
		$rec_shortcut_title 		= ! empty( $rec_shortcut['title'] ) ? $rec_shortcut['title'] : false;
		$rec_shortcut_text 			= ! empty( $rec_shortcut['text'] ) ? $rec_shortcut['text'] : false;
		$rec_shortcut_button_text 	= ! empty( $rec_shortcut['button_text'] ) ? $rec_shortcut['button_text'] : __( 'Läs mer', 'lnob' );

		?>

		<section id="section-introduction" class="introduction">

			<div class="intro-inner section-inner">

				<div class="intro-content-wrapper">

					<?php if ( $content_post_obj ) : ?>

						<div class="entry-content disable-alignwide mw-small">

							<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $content_post_obj->ID ) ); ?>

						</div><!-- .entry-content -->

					<?php endif; ?>

					<div class="rec-shortcut-wrapper pu-48 pu-tl-0 pl-tl-64">

						<div class="rec-shortcut bg-yellow p-24 p-t-40">

							<?php if ( $rec_shortcut_title ) : ?>
								<h2 class="rec-shortcut-title h6 no-margin"><?php echo $rec_shortcut_title; ?></h2>
							<?php endif; ?>

							<?php if ( $rec_shortcut_text ) : ?>
								<div class="rec-shortcut-text contain-margins pu-24 fs-16 fs-t-18"><?php echo wpautop( $rec_shortcut_text ); ?></div>
							<?php endif; ?>

							<div class="rec-shortcut-button pu-32">
								<a class="faux-button" href="#section-recommendations"><?php echo $rec_shortcut_button_text; ?></a>
							</div><!-- .rec-shortcut-button -->

						</div><!-- .rec-shortcut -->

					</div><!-- .rec-shortcut-wrapper -->

				</div><!-- .intro-content-wrapper -->

				<div class="intro-scroll-button-wrapper">
					<?php 
					echo lnob_get_squircle_link( array(
						'icon'	=> array(
							'name'	=> 'arrow-down',
						),
						'text'	=> $scroll_button_text,
						'url'	=> '#section-global-goals',
					) );
					?>
				</div><!-- .intro-scroll-button-wrapper -->

			</div><!-- .intro-inner -->

		</section><!-- #section-introduction -->

		<?php

		/* ----------------------------------- */
		/* Global Goals ---------------------- */
		/* ----------------------------------- */

		?>
		
		<div class="global-goals bg-black screen-height">

		</div>

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

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
		$share_show			= ! empty( $hero['share']['show'] ) ? $hero['share']['show'] : false;
		$share_settings 	= ! empty( $hero['share']['settings'] ) ? $hero['share']['settings'] : array();

		$share_settings['default']['permalink'] = home_url( '/' );

		?>

		<div class="hero-intro-wrapper">

			<section id="section-hero" class="hero pv-0">

				<div class="hero-inner section-inner">

					<div class="hero-header-placeholder"></div>

					<header class="page-header mw-small pu-48 pd-64 pu-t-64 pd-t-80 i-a a-fade-scale-up a-del-300">

						<?php if ( $title ) : ?>
							<h1 class="page-title no-margin"><?php echo $title; ?></h1>
						<?php endif; ?>

						<?php if ( $intro_text ) : ?>
							<div class="intro-text fs-intro-text contain-margins pu-24 pu-t-32"><?php echo wpautop( $intro_text ); ?></div>
						<?php endif; ?>

						<?php if ( $share_show ) : ?>

							<div class="social-wrapper pu-24 pu-t-32">
								<?php
								lnob_the_share_links( array(
									'parameters'	=> $share_settings
								) );
								?>
							</div><!-- .social-wrapper -->

						<?php endif; ?>
					
					</header><!-- .page-header -->

					<div class="scroll-button-wrapper i-a a-fade-in a-del-1000">
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

							<div class="mw-small footnotes-container">

								<div class="entry-content do-spot spot-fade-up">
									<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $content_post_obj ) ); ?>
								</div><!-- .entry-content -->

								<?php if ( $formatted_footnotes = lnob_get_formatted_footnotes( $content_post_obj->ID ) ) : ?>

									<div class="footnotes-content-wrapper do-spot spot-fade-up pu-32 pu-t-48">

										<?php echo $formatted_footnotes; ?>

									</div><!-- .footnotes-content-wrapper -->

								<?php endif; ?>

							</div><!-- .mw-small -->

						<?php endif; ?>

						<div class="rec-shortcut-wrapper pu-48 pu-tl-0 pl-tl-64">

							<div class="rec-shortcut bg-yellow p-24 p-t-40 do-spot spot-fade-up">

								<?php if ( $rec_shortcut_title ) : ?>
									<h2 class="rec-shortcut-title h6 no-margin"><?php echo $rec_shortcut_title; ?></h2>
								<?php endif; ?>

								<?php if ( $rec_shortcut_text ) : ?>
									<div class="rec-shortcut-text contain-margins pu-24 fs-16 fs-t-18 mw-readable"><?php echo wpautop( $rec_shortcut_text ); ?></div>
								<?php endif; ?>

								<div class="rec-shortcut-button pu-32">
									<a class="faux-button" href="#section-recommendations"><?php echo $rec_shortcut_button_text; ?></a>
								</div><!-- .rec-shortcut-button -->

							</div><!-- .rec-shortcut -->

						</div><!-- .rec-shortcut-wrapper -->

					</div><!-- .intro-content-wrapper -->

					<div class="intro-scroll-button-wrapper do-spot spot-fade-up">
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

		</div><!-- .hero-intro-wrapper -->

		<?php

		/* ----------------------------------- */
		/* Global Goals ---------------------- */
		/* ----------------------------------- */

		if ( $GLOBALS['global_goals'] ) : 
			?>
		
			<div id="section-global-goals" class="global-goals">

				<?php
				global $post;
				foreach ( $global_goals as $post ) : 
					setup_postdata( $post );

					get_template_part( 'inc/parts/global-goal' );

				endforeach;
				wp_reset_postdata();
				?>

			</div>

			<?php 
		endif;

		/* ----------------------------------- */
		/* Missing In Stats ------------------ */
		/* ----------------------------------- */

		$missing_in_stats 	= get_field( 'missing_in_stats' );
		$title 				= ! empty( $missing_in_stats['title'] ) ? $missing_in_stats['title'] : '';
		$intro_text 		= ! empty( $missing_in_stats['intro_text'] ) ? $missing_in_stats['intro_text'] : '';
		$content_post_obj 	= ! empty( $missing_in_stats['content_post_object'] ) ? $missing_in_stats['content_post_object'] : false;

		$share_show			= ! empty( $missing_in_stats['share']['show'] ) ? $missing_in_stats['share']['show'] : false;
		$share_settings 	= ! empty( $missing_in_stats['share']['settings'] ) ? $missing_in_stats['share']['settings'] : array();

		$share_settings['default']['permalink'] = home_url( '/' ) . '#section-missing-in-stats';

		?>

		<section id="section-missing-in-stats" class="missing-in-stats p-0">

			<header class="missing-header">

				<div class="missing-header-bg bg-yellow pu-64 pd-32 pu-t-96 pd-t-0">

					<div class="missing-header-inner section-inner pos-relative z-1">

						<div class="missing-header-grid grid cols-tl-6 no-v-gutter">

							<div class="col do-spot spot-fade-up">
								<?php if ( $title ) : ?>
									<h2 class="section-title h1 no-margin"><?php echo $title; ?></h2>
								<?php endif; ?>
							</div>

							<div class="col do-spot spot-fade-up a-del-300">
								<?php if ( $intro_text ) : ?>
									<div class="intro-text fs-intro-text contain-margins mw-readable pu-24 pu-t-32 pu-tl-0"><?php echo wpautop( $intro_text ); ?></div>
								<?php endif; ?>

								<?php if ( $share_show ) : ?>
									<div class="social-wrapper pu-24 pu-t-32">
										<?php lnob_the_share_links( array(
											'colors'		=> array(
												'icon'			=> 'white',
												'background'	=> 'black',
											),
											'parameters'	=> $share_settings,
										) ); ?>
									</div><!-- .social-wrapper -->
								<?php endif; ?>
							</div>

						</div><!-- .missing-header-grid -->

					</div><!-- .missing-header-inner -->

				</div><!-- .missing-header-bg -->

				<div class="section-sep">
					<?php lnob_the_svg( 'section-jagged-edge' ); ?>
				</div>

			</header><!-- .missing-header -->

			<?php if ( $content_post_obj ) : ?>

				<div class="entry-content-container pv-48 pd-64 pv-t-96">

					<div class="section-inner mw-thin footnotes-container">

						<div class="entry-content centered-content do-spot spot-fade-up">
							<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $content_post_obj ) ); ?>
						</div><!-- .entry-content -->

						<?php if ( $formatted_footnotes = lnob_get_formatted_footnotes( $content_post_obj ) ) : ?>
							<div class="footnotes-content-wrapper do-spot spot-fade-up pu-32 pu-t-48">
								<?php echo $formatted_footnotes; ?>
							</div><!-- .footnotes-content-wrapper -->
						<?php endif; ?>

					</div><!-- .mw-thin -->

				</div><!-- .entry-content-container -->

			<?php endif; ?>

		</section><!-- #section-missing-in-stats -->

		<?php

		/* ----------------------------------- */
		/* Recommendations ------------------- */
		/* ----------------------------------- */

		$recommendations 	= get_field( 'recommendations' );
		$title 				= ! empty( $recommendations['title'] ) ? $recommendations['title'] : '';
		$intro_text 		= ! empty( $recommendations['intro_text'] ) ? $recommendations['intro_text'] : '';
		$scroll_button_text = ! empty( $recommendations['scroll_button_text'] ) ? $recommendations['scroll_button_text'] : __( 'Scrolla', 'lnob' );
		$content_post_obj 	= ! empty( $recommendations['content_post_object'] ) ? $recommendations['content_post_object'] : false;

		$share_show			= ! empty( $recommendations['share']['show'] ) ? $recommendations['share']['show'] : false;
		$share_settings 	= ! empty( $recommendations['share']['settings'] ) ? $recommendations['share']['settings'] : array();

		$share_settings['default']['permalink'] = home_url( '/' ) . '#section-recommendations';

		?>

		<section id="section-recommendations" class="recommendations p-0 gg-1">

			<header class="recommendations-hero screen-height bg-yellow pu-64 pd-32 pd-tl-48 pd-d-64">

				<div class="recommendations-hero-inner section-inner">

					<div class="recommendations-hero-grid grid">

						<div class="col do-spot spot-fade-up">
							<?php if ( $title ) : 
								$title_classes = $title == 'Det här behöver göras' ? ' prettify-line-breaks' : '';
								?>
								<h2 class="recommendations-title h1 no-margin<?php echo $title_classes; ?>"><?php echo $title; ?></h2>
							<?php endif; ?>
						</div>

						<div class="col col-tl-6 do-spot spot-fade-up a-del-200">
							<?php if ( $intro_text ) : ?>
								<div class="intro-text fs-intro-text contain-margins mw-readable"><?php echo wpautop( $intro_text ); ?></div>
							<?php endif; ?>

							<?php if ( $share_show ) : ?>
								<div class="social-wrapper pu-24 pu-t-32">
									<?php lnob_the_share_links( array(
										'colors'		=> array(
											'icon'			=> 'white',
											'background'	=> 'black',
										),
										'parameters'	=> $share_settings
									) ); ?>
								</div><!-- .social-wrapper -->
							<?php endif; ?>
						</div>

						<div class="col col-tl-6 do-spot spot-fade-up a-del-400">

							<div class="scroll-button-wrapper">
								<?php 
								echo lnob_get_squircle_link( array(
									'icon'				=> array(
										'name'				=> 'arrow-down',
									),
									'link_classes'		=> array( 'large', 'fs-t-24' ),
									'squircle_color'	=> 'white',
									'text'				=> $scroll_button_text,
									'url'				=> '#recommendations-content',
								) );
								?>
							</div>

						</div>

					</div><!-- .recommendations-hero-grid -->

				</div><!-- .recommendations-hero-inner -->

			</header><!-- .recommendations-hero -->

			<?php if ( $content_post_obj ) : ?>

				<div class="section-inner mw-thin pv-48 pd-64 pv-t-96 footnotes-container" id="recommendations-content">

					<div class="entry-content centered-content">
						<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $content_post_obj ) ); ?>
					</div><!-- .entry-content -->

					<?php if ( $formatted_footnotes = lnob_get_formatted_footnotes( $content_post_obj ) ) : ?>
						<div class="footnotes-content-wrapper do-spot spot-fade-up pu-32 pu-t-48">
							<?php echo $formatted_footnotes; ?>
						</div><!-- .footnotes-content-wrapper -->
					<?php endif; ?>

				</div><!-- .section-inner -->

			<?php endif; ?>

		</section><!-- #section-recommendations -->

	</main><!-- #site-content -->

	<?php

endwhile;

get_footer();

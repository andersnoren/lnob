<?php
$gg_number 			= get_field( 'general_global_goal' ) ?: 1;
$gg_icon 			= 'gg/gg-' . $gg_number;
$gg_icon_symbol 	= 'gg/gg-' . $gg_number . '-symbol';
$gg_id				= 'gg-' . get_post_field( 'post_name', $post->ID );

$share_show			= ( get_post_meta( $post->ID, 'share_show', true ) === '' || get_post_meta( $post->ID, 'share_show', true ) === '1' );
$share_settings 	= get_field( 'share_settings' );

$share_settings['default']['permalink'] = get_permalink();

// Determine the text color for this global goal (ensures contrast).
// The contrasting color is the opposite of the text color.
$gg_text_c 			= lnob_get_gg_text_color( $gg_number );
$gg_text_contrast_c = $gg_text_c == 'white' ? 'black' : 'white';
?>

<section id="<?php echo esc_attr( $gg_id ); ?>" class="gg gg-<?php echo esc_attr( $gg_number ); ?> pv-0 footnotes-container" style="z-index: <?php echo $gg_number; ?>;">

	<?php
	// Set the --c-gg CSS variable to the color of the current section, for the current section.
	?>

	<style>
		#<?php echo $gg_id; ?> {
			--c-gg: var( --c-gg-<?php echo $gg_number; ?> );
		}
	</style>

	<div class="gg-hero screen-height bg-gg pos-relative">

		<div class="gg-hero-symbol pos-cover p-body-margin">
			<?php lnob_the_svg( $gg_icon_symbol ); ?>
		</div><!-- .gg-hero-symbol -->

		<?php
		$title 		= get_field( 'content_title' ) ?: get_the_title();
		$intro_text = get_field( 'content_intro_text' );
		?>

		<div class="gg-hero-inner section-inner pos-relative">

			<div class="gg-number ff-giorgio-sans c-<?php echo $gg_text_contrast_c; ?>">
				<span class="d-tl-none"><?php echo $gg_number; ?></span>
			</div>

			<div class="gg-hero-content pv-64 pv-t-96">

				<div class="gg-number ff-giorgio-sans pu-t-96 d-none d-tl-block c-<?php echo $gg_text_contrast_c; ?>">
					<div class="do-spot spot-fade-up a-del-200c"><?php echo $gg_number; ?></div>
				</div>
				
				<div class="do-spot spot-fade-up a-del-200c">

					<?php if ( $title ) : ?>
						<h2 class="gg-title ff-giorgio-sans fs-48 fs-t-80 fs-tl-96 no-margin c-<?php echo $gg_text_c; ?>"><?php echo $title; ?></h2>
					<?php endif; ?>

					<?php if ( $intro_text ) : ?>
						<div class="gg-intro-text fs-intro-text pu-24 pu-t-32 pu-tl-40 contain-margins mw-readable c-<?php echo $gg_text_c; ?>"><?php echo wpautop( $intro_text ); ?></div>
					<?php endif; ?>

					<?php if ( $share_show ) : ?>
						<div class="social-wrapper pu-24 pu-t-32 pu-tl-40">
							<?php lnob_the_share_links( array(
								'colors'		=> array(
									'background'	=> $gg_text_c,
									'icon'			=> $gg_text_contrast_c,
								),
								'copy_url'		=> get_permalink(),
								'parameters'	=> $share_settings,
							) ); ?>
						</div><!-- .social-wrapper -->
					<?php endif; ?>

				</div><!-- .do-spot -->

			</div><!-- .gg-hero-content -->

			<div class="scroll-button-wrapper">
				<?php 

				$expand_button_text 	= get_field( 'content_expand_button_text' ) ?: __( 'L??s mer', 'lnob' );
				$collapse_button_text 	= get_field( 'content_collapse_button_text' ) ?: __( 'St??ng', 'lnob' );

				$button_text = '<span class="show-inactive">' . $expand_button_text . '</span><span class="show-active">' . $collapse_button_text . '</span>';

				echo lnob_get_squircle_link( array(
					'icon'				=> array(
						'stroke-color'		=> $gg_text_contrast_c,
						'name'				=> 'plus',
					),
					'link_classes'		=> array( 'gg-content-toggle', 'large', 'fs-14', 'fs-t-18', 'fs-tl-24', 'c-' . $gg_text_c ),
					'text'				=> $button_text,
					'url'				=> '#',
					'squircle_color'	=> $gg_text_c,
					'attributes'		=> array(
						'data-toggle-target'	=> '#gg-' . $gg_number . '-content',
						'data-toggle-type'		=> 'slidetoggle',
					),
				) );
				?>
			</div>

		</div><!-- .gg-hero-inner -->

	</div><!-- .gg-hero -->

	<div class="gg-content" id="gg-<?php echo $gg_number; ?>-content">

		<div class="gg-content-inner screen-height">

			<div class="entry-content centered-content section-inner mw-thin pu-48 pd-32 pv-t-96">
				<?php the_content(); ?>
			</div><!-- .entry-content -->

			<div class="gg-footer section-inner">

				<div class="gg-actions d-t-flex justify-between">

					<?php if ( $share_show ) : ?>
						<div class="social-wrapper">
							<?php lnob_the_share_links( array(
								'copy_url'		=> get_permalink(),
								'parameters'	=> $share_settings,
							) ); ?>
						</div><!-- .social-wrapper -->
					<?php endif; ?>

					<?php 
					
					$footnotes = lnob_get_footnotes( $post->ID );

					if ( $footnotes ) : 
						?>

						<div class="footnotes-button-wrapper d-flex pu-64 pu-t-0 d-no-js-none">
							<button class="footnotes-button faux-button bg-gray-dark c-black width-full">
								<div class="icon-text">
									<div class="icon"><?php lnob_the_svg( 'book', 24, 24 ); ?></div>
									<div class="text">
										<span class="show-inactive"><?php _e( 'Visa k??llor', 'lnob' ); ?></span>
										<span class="show-active"><?php _e( 'D??lj k??llor', 'lnob' ); ?></span>
									</div>
								</div><!-- .icon-text -->
							</button>
						</div><!-- .footnotes-button-wrapper -->

						<?php
					endif;
					?>

				</div><!-- .gg-actions -->

				<?php if ( $footnotes ) : ?>

					<div class="footnotes-box-wrapper pu-24 pu-t-48 d-none d-no-js-block d-active-block">
						<div class="footnotes-box bg-gray-light contain-margins p-24 p-t-48">
							<?php echo $footnotes; ?>
						</div><!-- .footnotes-box -->
					</div><!-- .footnotes-box-wrapper -->

				<?php endif; ?>

				<div class="gg-info-boxes pu-24 pu-t-48">

					<div class="gg-info-boxes-grid grid cols-t-6">

						<div class="col">

							<?php
							$more_info_title 		= get_field( 'footer_more_info_box_title' ) ?: __( 'Vill du veta mer?', 'lnob' );
							$more_info_text			= get_field( 'footer_more_info_box_text' ) ?: sprintf( __( 'Du hittar mer information om m??l %s p?? Globala M??lens officiella webbplats.', 'lnob' ), $gg_number );
							$more_info_button_text	= get_field( 'footer_more_info_box_button_text' ) ?: __( 'Till webbplatsen', 'lnob' );
							$more_info_button_url	= get_field( 'footer_more_info_box_button_url' ) ?: lnob_get_gg_website_url( $gg_number );
							?>

							<div class="more-info bg-gg p-24 p-t-40 c-<?php echo $gg_text_c; ?>">

								<h3 class="more-info-title h6 no-margin"><?php echo $more_info_title; ?></h3>

								<div class="more-info-text contain-margins pu-24 fs-16 fs-t-18 mw-readable"><?php echo wpautop( $more_info_text ); ?></div>

								<div class="more-info-button pu-32">
									<a class="faux-button" href="<?php echo esc_url( $more_info_button_url ); ?>" target="_blank"><?php echo $more_info_button_text; ?></a>
								</div><!-- .more-info-button -->

							</div><!-- .more-info -->

						</div><!-- .col -->

						<div class="col">

							<?php
							// Get the fields for the recommendations box from the post if set, and from the front page introduction if not.
							$page_on_front 				= get_option( 'page_on_front' );
							$rec_shortcut_title 		= get_field( 'footer_recommendations_box_title' ) ?: get_field( 'introduction_recommendations_box_title', $page_on_front );
							$rec_shortcut_text 			= get_field( 'footer_recommendations_box_text' ) ?: get_field( 'introduction_recommendations_box_text', $page_on_front );
							$rec_shortcut_button_text 	= get_field( 'footer_recommendations_box_button_text' ) ?: get_field( 'introduction_recommendations_box_button_text', $page_on_front ) ?: __( 'L??s mer', 'lnob' );
							?>

							<div class="rec-shortcut bg-yellow p-24 p-t-40">

								<?php if ( $rec_shortcut_title ) : ?>
									<h3 class="rec-shortcut-title h6 no-margin"><?php echo $rec_shortcut_title; ?></h3>
								<?php endif; ?>

								<?php if ( $rec_shortcut_text ) : ?>
									<div class="rec-shortcut-text contain-margins pu-24 fs-16 fs-t-18 mw-readable"><?php echo wpautop( $rec_shortcut_text ); ?></div>
								<?php endif; ?>

								<div class="rec-shortcut-button pu-32">
									<a class="faux-button" href="#section-recommendations"><?php echo $rec_shortcut_button_text; ?></a>
								</div><!-- .rec-shortcut-button -->

							</div><!-- .rec-shortcut -->

						</div><!-- .col -->

					</div><!-- .gg-info-boxes-grid -->

				</div><!-- .gg-info-boxes -->

				<div class="gg-content-untoggle-wrapper d-flex justify-center">

					<button class="gg-content-untoggle reset" data-toggle-target="#gg-<?php echo $gg_number; ?>-content" data-toggle-type="toggle">
						<div class="icon-text pv-32">
							<div class="icon fill-cc"><?php lnob_the_svg( 'cross', 16, 16 ); ?></div>
							<div class="text h-uppercase fs-14"><?php printf( __( 'St??ng m??l %s', 'lnob' ), $gg_number ); ?></div>
						</div><!-- .icon-text -->
					</button><!-- .gg-content-untoggle -->

				</div><!-- .gg-content-untoggle-wrapper -->

			</div><!-- .gg-footer -->

		</div><!-- .gg-content-inner -->

	</div><!-- .gg-content -->

</section><!-- .gg -->
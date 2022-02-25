<?php
$gg_number 			= get_field( 'general_global_goal' ) ?: 1;
$gg_icon 			= 'gg/gg-' . $gg_number;
$gg_icon_symbol 	= 'gg/gg-' . $gg_number . '-symbol';


// Determine the text color for this global goal (ensures contrast).
// The contrasting color is the opposite of the text color.
$gg_text_c = lnob_get_gg_text_color( $gg_number );
$gg_text_contrast_c = $gg_text_c == 'white' ? 'black' : 'white';
?>

<section id="gg-<?php echo $gg_number; ?>" class="gg pv-0">

	<?php
	// Set the --c-gg CSS variable to the color of the current section, for the current section.
	?>

	<style>
		#gg-<?php echo $gg_number; ?> {
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

				<div class="gg-number ff-giorgio-sans pu-t-96 d-none d-tl-block c-<?php echo $gg_text_contrast_c; ?>"><?php echo $gg_number; ?></div>

				<?php if ( $title ) : ?>
					<h2 class="gg-title ff-giorgio-sans fs-48 fs-t-80 fs-tl-96 no-margin c-<?php echo $gg_text_c; ?>"><?php echo $title; ?></h2>
				<?php endif; ?>

				<?php if ( $intro_text ) : ?>
					<div class="gg-intro-text fs-intro-text pu-24 pu-t-32 pu-tl-40 contain-margins mw-small c-<?php echo $gg_text_c; ?>"><?php echo wpautop( $intro_text ); ?></div>
				<?php endif; ?>

				<div class="social-wrapper pu-24 pu-t-32 pu-tl-40">
					<?php lnob_the_share_links( array(
						'colors'	=> array(
							'background'	=> $gg_text_c,
							'icon'			=> $gg_text_contrast_c,
						),
					) ); ?>
				</div><!-- .social-wrapper -->

			</div><!-- .gg-hero-content -->

			<div class="scroll-button-wrapper">
				<?php 
				echo lnob_get_squircle_link( array(
					'icon'				=> array(
						'stroke-color'		=> $gg_text_contrast_c,
						'name'				=> 'plus',
					),
					'link_classes'		=> array( 'toggleable', 'large', 'fs-14', 'fs-t-18', 'fs-tl-24', 'c-' . $gg_text_c ),
					'text'				=> 'Läs mer',
					'url'				=> '#gg-' . $gg_number . '-content',
					'squircle_color'	=> $gg_text_c,
				) );
				?>
			</div>

		</div><!-- .gg-hero-inner -->

	</div><!-- .gg-hero -->

	<div class="gg-content d-none" id="gg-<?php echo $gg_number; ?>-content">

		<div class="gg-content-inner section-inner">

		</div><!-- .gg-content-inner -->

	</div><!-- .gg-content -->

</section><!-- .gg -->
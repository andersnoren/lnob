<?php
/*
 * ACF Block: Numbers
 * 
 * Note: The markup and styles for the block is in the theme, but the block registration and ACF fields are stored in the compatibility plugin.
 */

// Create id attribute allowing for custom "anchor" value.
$id = ! empty( $block['anchor'] ) ? $block['anchor'] : 'numbers-' . $block['id'];

// Create class attribute allowing for custom "className" values.
$class_name = 'lnob-block-numbers';
if ( ! empty( $block['className'] ) ) $class_name .= ' ' . $block['className'];

// Prepare variables
$number_p 	= get_field( 'content_number_prefix' );
$number 	= get_field( 'content_number' );
$number_s 	= get_field( 'content_number_suffix' );
$text 		= get_field( 'content_text' );

$bg_color 	= get_field( 'appearance_color_bg' );

$share		= get_field( 'share' );

// Handle ACF Blocks nesting of group values.
if ( isset( $share['share'] ) ) $share = $share['share'];

$share_show			= ( isset( $share['show'] ) && ( $share['show'] === '' || $share['show'] == '1' ) ) || ( ! isset( $share['show'] ) );
$share_settings 	= ! empty( $share['settings'] ) ? $share['settings'] : array();

$share_settings['default']['permalink'] = home_url() . '#' . $id;

// Determine the text color for this global goal (ensures contrast).
// The contrasting color is the opposite of the text color.
global $post;
$gg_number 			= get_field( 'general_global_goal', $post->ID ) ?: 1;
$gg_text_c 			= lnob_get_gg_text_color( $gg_number );
$gg_text_contrast_c = $gg_text_c == 'white' ? 'black' : 'white';

$block_inner_classes = '';
$block_number_classes = '';
$block_social_icon = '';
$block_social_bg = '';

if ( $bg_color == 'none' ) {
	$block_number_classes .= ' c-gg';
	$block_social_icon = $gg_text_c;
	$block_social_bg = 'gg';
} else {

	$class_name .= ' has-background';
	$block_inner_classes .= ' ph-24 pv-32 p-t-48 p-d-64 br-squircle';
	$block_social_icon = 'black';
	$block_social_bg = 'white';

	if ( $bg_color == 'gg' ) {
		$block_inner_classes .= ' bg-gg c-' . $gg_text_c;
	} else if ( $bg_color == 'yellow' ) {
		$block_inner_classes .= ' bg-yellow c-black';
	}
}

?>

<div class="<?php echo esc_attr( $class_name ); ?> do-spot" id="<?php echo esc_attr( $id ); ?>">

	<div class="block-inner<?php echo $block_inner_classes; ?>">

		<?php if ( $number_p || $number || $number_s ) : ?>
			<div class="numbers-number ff-avenir-next-condensed h1 no-margin fs-40 fs-t-48 fs-tl-64 ta-c<?php echo $block_number_classes; ?>">
				<?php 
				if ( $number_p ) echo '<span class="number-prefix">' . $number_p . '</span>';
				if ( $number ) echo '<span class="number-value count-up">' . $number . '</span>';
				if ( $number_s ) echo '<span class="number-suffix">' . $number_s . '</span>';
				?>
			</div><!-- .numbers-number -->
		<?php endif; ?>

		<?php if ( $text ) : ?>
			<div class="numbers-text mw-readable center pu-16 contain-margins ta-c fs-16 fs-t-18 fs-tl-20 fw-600">
				<?php echo wpautop( $text ); ?>
			</div><!-- .numbers-text -->
		<?php endif; ?>

		<?php if ( $share_show ) : ?>
			<div class="social-wrapper d-flex justify-center pu-24 pu-t-32">
				<?php lnob_the_share_links( array(
					'colors'	=> array(
						'icon'			=> $block_social_icon,
						'background'	=> $block_social_bg,
					),
					'parameters'	=> $share_settings,
				) ); ?>
			</div><!-- .social-wrapper -->
		<?php endif; ?>

	</div><!-- .block-inner -->

</div><!-- .lnob-block-numbers -->
<?php
/*
 * ACF Block: Numbers
 * 
 * Note: The markup and styles for the block is in the theme, but the block registration and ACF fields are stored in the compatibility plugin.
 */

// Create id attribute allowing for custom "anchor" value.
$id = ! empty( $block['anchor'] ) ? $block['anchor'] : 'products-' . $block['id'];

// Create class attribute allowing for custom "className" values.
$class_name = 'lnob-block-numbers';
if ( ! empty( $block['className'] ) ) $class_name .= ' ' . $block['className'];

// Prepare variables
$number_prefix 	= get_field( 'content_number_prefix' );
$number 		= get_field( 'content_number' );
$number_suffix 	= get_field( 'content_number_suffix' );
$text 			= get_field( 'content_text' );

$bg_color 		= get_field( 'appearance_color_bg' );

// Determine the text color for this global goal (ensures contrast).
// The contrasting color is the opposite of the text color.
global $post;
$gg_number 			= get_field( 'general_global_goal', $post->ID ) ?: 1;
$gg_text_c 			= lnob_get_gg_text_color( $gg_number );
$gg_text_contrast_c = $gg_text_c == 'white' ? 'black' : 'white';

?>

<div class="<?php echo esc_attr( $class_name ); ?>" id="<?php echo esc_attr( $id ); ?>">

	<div class="block-inner bg-yellow br-squircle color-black p-24 pd-32 p-t-48">

		<?php if ( $text ) : ?>
			<div class="numbers-text mw-readable center contain-margins ta-c">
				<?php echo wpautop( $text ); ?>
			</div><!-- .numbers-text -->
		<?php endif; ?>

		<div class="social-wrapper d-flex justify-center pu-24 pu-t-32">
			<?php lnob_the_share_links( array(
				'colors'	=> array(
					'icon'			=> 'black',
					'background'	=> 'white',
				),
			) ); ?>
		</div><!-- .social-wrapper -->

	</div><!-- .block-inner -->

</div><!-- .lnob-block-numbers -->
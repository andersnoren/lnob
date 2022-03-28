<?php
/*
 * ACF Block: Recommendation
 * 
 * Note: The markup and styles for the block is in the theme, but the block registration and ACF fields are stored in the compatibility plugin.
 */

// Create id attribute allowing for custom "anchor" value.
$id = ! empty( $block['anchor'] ) ? $block['anchor'] : 'recommendation-' . $block['id'];

// Create class attribute allowing for custom "className" values.
$class_name = 'lnob-block-recommendation';
if ( ! empty( $block['className'] ) ) $class_name .= ' ' . $block['className'];

// Prepare variables
$text 				= get_field( 'content_text' );
$share				= get_field( 'share' );

// Handle ACF Blocks nesting of group values.
if ( isset( $share['share'] ) ) $share = $share['share'];

$share_show			= ( isset( $share['show'] ) && ( $share['show'] === '' || $share['show'] == '1' ) ) || ( ! isset( $share['show'] ) );
$share_settings 	= ! empty( $share['settings'] ) ? $share['settings'] : array();

$share_settings['default']['permalink'] = home_url( '/' ) . '#' . $id;

?>

<div class="<?php echo esc_attr( $class_name ); ?>" id="<?php echo esc_attr( $id ); ?>">

	<div class="block-inner bg-yellow br-squircle color-black p-24 pd-32 p-t-48">

		<?php if ( $text ) : ?>
			<div class="recommendation-text mw-readable center contain-margins ta-c">
				<?php echo wpautop( $text ); ?>
			</div><!-- .recommendation-text -->
		<?php endif; ?>

		<?php if ( $share_show ) : ?>
			<div class="social-wrapper d-flex justify-center pu-24 pu-t-32">
				<?php lnob_the_share_links( array(
					'colors'		=> array(
						'icon'			=> 'black',
						'background'	=> 'white',
					),
					'parameters'	=> $share_settings,
				) ); ?>
			</div><!-- .social-wrapper -->
		<?php endif; ?>

	</div><!-- .block-inner -->

</div><!-- .lnob-block-recommendation -->
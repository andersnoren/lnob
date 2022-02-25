<?php

/*  -----------------------------------------------------------------------------------------------
 * 	TEMPLATE TAGS
 * 
 * 	All custom theme functions not hooked to an action or a filter goes here.
 * 	If it's hooked to something, it goes in functions.php.
 * 
 */


/*	-----------------------------------------------------------------------------------------------
	GET THE FALLBACK IMAGE URL
--------------------------------------------------------------------------------------------------- */

function lnob_get_fallback_image_url() {
	return LNOB_TEMPLATE_DIR_URI . '/assets/images/fallback-image.png';
}


/*	-----------------------------------------------------------------------------------------------
	GET AND OUTPUT THEME SVG
	Get and output the markup for a SVG file in the theme /icons/ folder.

	@param		$svg_name string	The file name of the SVG file.
	@param		$width int			The width of the element (use if constant across screen sizes).
	@param		$height int			The height of the element (use if constant across screen sizes).
--------------------------------------------------------------------------------------------------- */

function lnob_the_svg( $svg_name, $width = null, $height = null ) {
	echo lnob_get_svg( $svg_name, $width, $height );
}

function lnob_get_svg( $svg_name, $width = null, $height = null ) {

	// Make sure we have a file extension on the string.
	if ( substr( $svg_name, -4 ) !== '.svg' ) {
		$svg_name .= '.svg';
	}

	$svg = file_get_contents( locate_template( '/assets/images/svgs/' . $svg_name ) );

	$style_attr = '';

	// Maybe add an inline style attribute for height and width.
	if ( $height || $width ) {

		$style_attr = ' style="';
		if ( $height ) 	$style_attr .= 'height:' . $height . 'px;';
		if ( $width ) 	$style_attr .= 'width:' . $width . 'px;';
		$style_attr .= '"';

	}

	$repl = '<svg class="svg-icon" aria-hidden="true" role="img" focusable="false"' . $style_attr . ' ';
	$svg  = preg_replace( '/^<svg /', $repl, $svg ); // Add extra attributes to SVG code.
	$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
	$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

	return $svg;

}


/*	-----------------------------------------------------------------------------------------------
	RETURN FACEBOOK APP ID
--------------------------------------------------------------------------------------------------- */

function lnob_get_facebook_app_id() {
	return '';
}


/*	-----------------------------------------------------------------------------------------------
	GET EXTERNAL LINKS LIST MARKUP
	Returns markup for a list with external links, styled as an external arrow on a squircle and 
	link text to the right. Built for a ACF repeater with link fields.

	@param array $args {
		@type array $links_data {
			{
				@type str	$link_type		Can be post or url. Determines which data to use.
				@type arr	$link {			When $link_type is set to `url`.
					@type str 	$title			The title of the link.
					@type str 	$url			The URL of the link.
					@type str 	$target			The target of the link.
				}
				@type obj	$link_post			The post object to link to.
				@type obj	$link_text			The link text for the post object link.
			}
		}
		@type str	$default_link_text	The default link text.
	}

	@return string 	$html	The HTML for the links.
--------------------------------------------------------------------------------------------------- */

function lnob_get_external_links_list_markup( $args ) {

	$args = wp_parse_args( $args, array(
		'links_data'		=> array(),
		'default_link_text'	=> __( 'Läs mer', 'lnob' ),
	) );

	if ( ! $args['links_data'] ) return;

	$links_to_output = false;

	ob_start();

	?>

	<ul class="external-links reset-list-style">

		<?php foreach ( $args['links_data'] as $link ) :

			$text 	= ! empty( $link['link']['title'] ) ? $link['link']['title'] : $args['default_link_text'];
			$url 	= ! empty( $link['link']['url'] ) ? $link['link']['url'] : '';
			$target = ! empty( $link['link']['target'] ) ? $link['link']['target'] : '';

			if ( ! $url ) continue;

			$link = lnob_get_squircle_link( array(
				'url'			=> $url,
				'text'			=> $text,
				'target'		=> $target,
				'link_classes' 	=> array( 'small' )
			) );
			
			if ( ! $link ) continue;

			$links_to_output = true;

			?>

			<li>
				<?php echo $link; ?>
			</li>

			<?php
		endforeach;
		?>

	</ul><!-- .external-links -->

	<?php

	if ( $links_to_output ) {
		return ob_get_clean();
	} else {
		ob_end_clean();
		return '';
	}

}


/*	-----------------------------------------------------------------------------------------------
	GET SQUIRCLE LINK
	Helper class for building markup for a squircle link, with squirlce + icon to the left and text 
	to the right.
--------------------------------------------------------------------------------------------------- */

function lnob_get_squircle_link( $args ) {

	$args = wp_parse_args( $args, array(
		'url'				=> '',
		'text'				=> '',
		'target'			=> '',
		'link_classes'		=> array(),
		'icon'				=> array(
			'stroke-color'		=> '',
			'fill-color'		=> '',
			'name'				=> 'arrow-external',
			'height'			=> null,
			'width'				=> null,
		),
		'squircle_color'	=> 'yellow',
	) );

	if ( ! $args['url'] ) return;

	$target_attr 		= $args['target'] ? ' target="' . $args['target'] . '"' : '';
	$link_classes_str	= $args['link_classes'] ? ' ' . implode( ' ', $args['link_classes'] ) : '';

	ob_start();

	$icon_name 		= ! empty( $args['icon']['name'] ) ? $args['icon']['name'] : '';
	$icon_width 	= ! empty( $args['icon']['width'] ) ? $args['icon']['width'] : '';
	$icon_height 	= ! empty( $args['icon']['height'] ) ? $args['icon']['height'] : '';

	$icon_class = '';
	if ( ! empty( $args['icon']['stroke-color'] ) ) $icon_class .= ' stroke-cc c-' . $args['icon']['stroke-color'];
	if ( ! empty( $args['icon']['fill-color'] ) ) $icon_class .= ' fill-cc c-' . $args['icon']['fill-color'];

	$link_classes_str .= $icon_name ? ' icon-' . $icon_name : '';

	?>

	<a class="squircle-link<?php echo esc_attr( $link_classes_str ); ?>" href="<?php echo esc_url( $args['url'] ); ?>"<?php echo $target_attr; ?>>
		<div class="squircle-wrapper pos-relative">
			<div class="squircle pos-cover fill-cc c-<?php echo $args['squircle_color']; ?>">
				<?php lnob_the_svg( 'squircle' ); ?>
			</div>
			<span class="icon<?php echo $icon_class; ?>"><?php lnob_the_svg( $icon_name, $icon_width, $icon_height ); ?></span>
		</div>
		<?php if ( $args['text'] ) : ?>
			<span class="text"><?php echo $args['text']; ?></span>
		<?php endif; ?>
	</a>

	<?php

	return ob_get_clean();
		
}


/*	-----------------------------------------------------------------------------------------------
	OUTPUT SHARE LINKS
	Output share links with the specified settings.
--------------------------------------------------------------------------------------------------- */

function lnob_the_share_links( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'colors'		=> array(
			'background'	=> 'service',
			'icon'			=> 'white',
		),
		'parameters'	=> array(
			'title'			=> '',
			'excerpt'		=> '',
			'permalink'		=> '',
			'media'			=> '',
		),
	) );

	$facebook_url = lnob_get_share_url( array(
		'service'		=> 'facebook',
		'parameters'	=> $args['parameters'],
	) );

	$twitter_url = lnob_get_share_url( array(
		'service'		=> 'twitter',
		'parameters'	=> $args['parameters'],
	) );

	$linkedin_url = lnob_get_share_url( array(
		'service'		=> 'linkedin',
		'parameters'	=> $args['parameters'],
	) );

	?>

	<div class="share-buttons">

		<ul class="share-buttons-list horizontal-list reset-list-style">

			<?php if ( $facebook_url ) : 
				$squircle_color = $args['colors']['background'] == 'service' ? 'facebook' : $args['colors']['background'];
				?>
				<li class="share-facebook">
					<?php 
					echo lnob_get_squircle_link( array(
						'url'				=> $facebook_url,
						'target'			=> '_blank',
						'icon'				=> array(
							'fill-color'		=> $args['colors']['icon'],
							'name'				=> 'social/facebook',
						),
						'squircle_color'	=> $squircle_color,
					) ); 
					?>
				</li>
			<?php endif; ?>

			<?php if ( $linkedin_url ) : 
				$squircle_color = $args['colors']['background'] == 'service' ? 'linkedin' : $args['colors']['background'];
				?>
				<li class="share-linkedin">
					<?php 
					echo lnob_get_squircle_link( array(
						'url'				=> $linkedin_url,
						'target'			=> '_blank',
						'icon'				=> array(
							'fill-color'		=> $args['colors']['icon'],
							'name'				=> 'social/linkedin',
						),
						'squircle_color'	=> $squircle_color,
					) ); 
					?>
				</li>
			<?php endif; ?>

			<?php if ( $twitter_url ) : 
				$squircle_color = $args['colors']['background'] == 'service' ? 'twitter' : $args['colors']['background'];
				?>
				<li class="share-twitter">
					<?php 
					echo lnob_get_squircle_link( array(
						'url'				=> $twitter_url,
						'target'			=> '_blank',
						'icon'				=> array(
							'fill-color'		=> $args['colors']['icon'],
							'name'				=> 'social/twitter',
						),
						'squircle_color'	=> $squircle_color,
					) ); 
					?>
				</li>
			<?php endif; ?>

		</ul>

	</div><!-- .share-buttons -->

	<?php

}


/*	-----------------------------------------------------------------------------------------------
	GET SHARE URL
	Get the URL for sharing the current page.
--------------------------------------------------------------------------------------------------- */

function lnob_get_share_url( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'service'		=> 'facebook',
		'parameters'	=> array(
			'title'			=> '',
			'excerpt'		=> '',
			'permalink'		=> '',
			'media'			=> '',
		),
	) );

	global $post;

	// Get the post variables for the sharing links
	$title 				= ! empty( $args['parameters']['title'] ) ? $args['parameters']['title'] : ( wp_strip_all_tags( get_the_title( $post ) ) );
	$excerpt 			= ! empty( $args['parameters']['excerpt'] ) ? $args['parameters']['excerpt'] : ( get_field( 'content_intro_text', $post ) ? wp_strip_all_tags( get_field( 'content_intro_text', $post ) ) : '' );
	$permalink 			= ! empty( $args['parameters']['permalink'] ) ? $args['parameters']['permalink'] : ( get_permalink( $post ) );
	$media 				= ! empty( $args['parameters']['media'] ) ? $args['parameters']['media'] : ( has_post_thumbnail( $post ) ? get_the_post_thumbnail_url( $post, 'large' ) : '' );
	$facebook_app_id 	= lnob_get_facebook_app_id();
	
	switch ( $args['service'] ) {
		case 'facebook' : 
			$url = 'https://www.facebook.com/dialog/feed?app_id=' . $facebook_app_id . '&display=page&redirect_uri=' . esc_url( $permalink ) . '&caption=' . esc_attr( $title ) . '&description=' . esc_attr( $excerpt ) . '&link=' . esc_url( $permalink ) . '&picture=' . esc_url( $media );
			break;
		case 'twitter' : 
			$url = 'http://twitter.com/share?text=' . esc_attr( $title ) . '&url=' . esc_url( $permalink );
			break;
		case 'linkedin' : 
			$url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '&title=' . $title;
			break;
	}

	return $url;
	
}


/*	-----------------------------------------------------------------------------------------------
	GET GLOBAL GOAL TEXT COLOR
--------------------------------------------------------------------------------------------------- */

function lnob_get_gg_text_color( $gg_number ) {

	switch ( $gg_number ) {

		case '1' :
		case '4' :
		case '8' :
		case '10' :
		case '13' :
		case '14' :
		case '16' :
		case '17' :
			return 'white';
			break;

		case '2' :
		case '3' :
		case '5' :
		case '6' :
		case '7' :
		case '9' :
		case '11' :
		case '12' :
		case '15' :
			return 'black';
			break;

		default : 
			return 'white';

	}
	
}
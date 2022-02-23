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

			$text 			= ! empty( $link['link']['title'] ) ? $link['link']['title'] : $args['default_link_text'];
			$url 			= ! empty( $link['link']['url'] ) ? $link['link']['url'] : '';
			$target 		= ! empty( $link['link']['target'] ) ? $link['link']['target'] : '';
			$target_attr 	= $target ? ' target="' . $target . '"' : '';

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
		'link_classes'			=> array(),
		'icon'				=> array(
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

	?>

	<a class="squircle-link<?php echo esc_attr( $link_classes_str ); ?>" href="<?php echo esc_url( $args['url'] ); ?>"<?php echo $target_attr; ?>>
		<div class="squircle-wrapper pos-relative">
			<div class="squircle pos-cover fill-cc c-<?php echo $args['squircle_color']; ?>">
				<?php lnob_the_svg( 'squircle' ); ?>
			</div>
			<span class="icon"><?php lnob_the_svg( $args['icon']['name'], $args['icon']['width'], $args['icon']['height'] ); ?></span>
		</div>
		<span class="text"><?php echo $args['text']; ?></span>
	</a>

	<?php

	return ob_get_clean();
		
}
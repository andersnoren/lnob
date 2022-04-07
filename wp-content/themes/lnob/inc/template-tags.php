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
	return '661175455109581';
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
		'attributes'		=> array() // A nested array with a [name]=[value] structure.
	) );

	if ( ! $args['url'] ) return;

	$attributes_str = '';

	if ( ! empty( $args['attributes'] ) ) {
		foreach ( $args['attributes'] as $name => $value ) {
			if ( $name ) $attributes_str .= ' ' . $name;
			if ( $value ) $attributes_str .= '="' . $value . '"';
		}
	}

	if ( $args['target'] ) $attributes_str .= ' target="' . $args['target'] . '"';

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

	<a class="squircle-link<?php echo esc_attr( $link_classes_str ); ?>" href="<?php echo esc_url( $args['url'] ); ?>"<?php echo $attributes_str; ?>>
		<div class="squircle-wrapper pos-relative">
			<div class="squircle pos-cover fill-cc c-<?php echo $args['squircle_color']; ?>">
				<?php lnob_the_svg( 'squircles/squircle' ); ?>
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
		'copy_url'		=> false,
		'parameters'	=> array(
			'default'		=> array(
				'title'			=> '',
				'permalink'		=> '',
			),
			'facebook'	=> array(
				'title'			=> '',
			),
			'linkedin'	=> array(),
			'twitter'	=> array(
				'title'			=> '',
			),
		),
	) );

	$default_title 		= ! empty( $args['parameters']['default']['title'] ) ? $args['parameters']['default']['title'] : '';
	$default_permalink 	= ! empty( $args['parameters']['default']['permalink'] ) ? $args['parameters']['default']['permalink'] : '';

	$facebook_parameters = array();
	$facebook_parameters['title'] 		= ! empty( $args['parameters']['facebook']['title'] ) ? $args['parameters']['facebook']['title'] : $default_title;
	$facebook_parameters['permalink'] 	= $default_permalink;

	$facebook_url = lnob_get_share_url( array(
		'service'		=> 'facebook',
		'parameters'	=> $facebook_parameters,
	) );

	$twitter_parameters = array();
	$twitter_parameters['title'] 		= ! empty( $args['parameters']['twitter']['title'] ) ? $args['parameters']['twitter']['title'] : $default_title;
	$twitter_parameters['permalink'] 	= $default_permalink;

	$twitter_url = lnob_get_share_url( array(
		'service'		=> 'twitter',
		'parameters'	=> $twitter_parameters,
	) );

	$linkedin_parameters = array();
	$linkedin_parameters['permalink'] 	= $default_permalink;

	$linkedin_url = lnob_get_share_url( array(
		'service'		=> 'linkedin',
		'parameters'	=> $linkedin_parameters,
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

			<?php if ( $args['copy_url'] ) : 
				$squircle_color = $args['colors']['background'] == 'service' ? 'black' : $args['colors']['background'];
				?>

				<li class="share-copy-url">
					<a class="squircle-link copy-link" data-url="<?php echo esc_url( $args['copy_url'] ); ?>" href="#">
						<div class="squircle-wrapper pos-relative">
							<div class="squircle pos-cover fill-cc c-<?php echo $squircle_color; ?>">
								<?php lnob_the_svg( 'squircles/squircle' ); ?>
							</div>
							<div class="icon-wrapper pos-cover c-<?php echo $args['colors']['icon']; ?>">
								<div class="copy-icon d-flex align-center justify-center icon-link fill-cc pos-cover"><?php lnob_the_svg( 'link', 20, 20 ); ?></div>
								<div class="copy-icon d-flex align-center justify-center icon-checkmark fill-cc pos-cover"><?php lnob_the_svg( 'checkmark', 20, 20 ); ?></div>
							</div>
							<div class="copy-text">
								<div class="copy"><?php _e( 'Kopiera länk', 'lnob' ); ?></div>
								<div class="copied"><?php _e( 'Länk kopierad!', 'lnob' ); ?></div>
							</div>
						</div>
					</a>
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
			'permalink'		=> '',
		),
	) );

	global $post;

	// Get the post variables for the sharing links
	$twitter_title 		= ! empty( $args['parameters']['title'] ) ? $args['parameters']['title'] : get_bloginfo( 'name' );
	$facebook_title 	= ! empty( $args['parameters']['title'] ) ? $args['parameters']['title'] : '';
	$permalink 			= ! empty( $args['parameters']['permalink'] ) ? $args['parameters']['permalink'] : home_url();
	$facebook_app_id 	= lnob_get_facebook_app_id();
	
	switch ( $args['service'] ) {
		case 'facebook' : 
			$url = 'https://facebook.com/dialog/share?app_id=' . $facebook_app_id . '&display=popup&href=' . urlencode( $permalink );
			if ( $facebook_title ) $url .= '&quote=' . urlencode( esc_attr( $facebook_title ) );
			break;
		case 'twitter' : 
			$url = 'https://twitter.com/intent/tweet?text=' . urlencode( esc_attr( $twitter_title ) ) . esc_attr( ' ' ) . urlencode( $permalink );
			break;
		case 'linkedin' : 
			$url = 'https://linkedin.com/sharing/share-offsite/?url=' . urlencode( $permalink );
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


/*	-----------------------------------------------------------------------------------------------
	GET GLOBAL GOAL WEBSITE URL
	Returns the URL for the global goal on the globalamalen.se website.
--------------------------------------------------------------------------------------------------- */

function lnob_get_gg_website_url( $gg_number ) {

	$url = '';

	switch ( $gg_number ) {

		case '1' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-1-ingen-fattigdom/';
			break;

		case '2' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-2-ingen-hunger/';
			break;

		case '3' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-3-halsa-och-valbefinnande/';
			break;

		case '4' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-4-god-utbildning-alla/';
			break;

		case '5' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-5-jamstalldhet/';
			break;

		case '6' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-6-rent-vatten-och-sanitet/';
			break;

		case '7' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-7-hallbar-energi-alla/';
			break;

		case '8' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-8-anstandiga-arbetsvillkor-och-ekonomisk-tillvaxt/';
			break;

		case '9' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-9-hallbar-industri-innovationer-och-infrastruktur/';
			break;

		case '10' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-10-minskad-ojamlikhet/';
			break;

		case '11' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-11-hallbara-stader-och-samhallen/';
			break;

		case '12' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-12-hallbar-konsumtion-och-produktion/';
			break;

		case '13' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-13-bekampa-klimatforandringarna/';
			break;

		case '14' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-14-hav-och-marina-resurser/';
			break;

		case '15' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-15-ekosystem-och-biologisk-mangfald/';
			break;

		case '16' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-16-fredliga-och-inkluderande-samhallen/';
			break;

		case '17' :
			$url = 'https://www.globalamalen.se/om-globala-malen/mal-17-genomforande-och-globalt-partnerskap/';
			break;

	}

	return $url;
	
}
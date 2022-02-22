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
	POST THUMBNAIL URL WITH FALLBACK
	Middleman function for getting or outputting the fallback image if no post thumbnail is available.

	@param	$post int/obj	ID or object of the post for which to retrieve the thumbnail.
	@param	$size string	The image size to retrieve the image in.
--------------------------------------------------------------------------------------------------- */

function lnob_the_post_thumbnail_url( $post = null, $size = 'post-thumbnail' ) {
	global $post;
	echo esc_url( lnob_get_the_post_thumbnail_url( $post, $size ) );
}

function lnob_get_the_post_thumbnail_url( $post = null, $size = 'post-thumbnail' ) {
	return get_the_post_thumbnail_url( $post, $size ) ?: lnob_get_fallback_image_url();
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
	BREADCRUMBS
	Output breadcrumbs.

	@param array $args {
		@type int		$post_id				The ID of the post to output the breadcrumbs for.
		@type bool		$display_term			Whether to display the first term the post belongs to.
		@type string	$additional_classes		Extra classes for the breadcrumbs wrapper.
	}
--------------------------------------------------------------------------------------------------- */

function lnob_breadcrumbs( $args = array() ) {

	$args = wp_parse_args( $args, array(
		'post_id'				=> null,
		'additional_classes'	=> '',
	) );

	// Don't output the breadcrumbs on the front page.
	if ( is_front_page() ) return;

	$post_id 				= $args['post_id'];
	$additional_classes 	= $args['additional_classes'] ? ' ' . $args['additional_classes'] : '';

	// Get the post object.
	if ( is_home() && ! is_front_page() ) {
		$post_id 	= get_option( 'page_for_posts' );
		$post 		= get_post( $post_id );
	} elseif ( $post_id ) {
		$post = get_post( $post_id );
	} elseif ( is_single() || is_page() || is_attachment() ) {
		global $post;
		if ( $post ) {
			$post_id = $post->ID;
		}
	} 

	// Get post related variables.
	if ( isset( $post ) && $post ) {
		$post_type 		= get_post_type( $post_id );
		$post_type_obj 	= get_post_type_object( $post_type );
	}

	// Get the queried object.
	$queried_object = null;
	if ( get_queried_object() ) {
		$queried_object = get_queried_object();
	}

	// Get the id of the page for posts, if one exists.
	$page_for_posts_id = get_option( 'page_for_posts' );

	// Specify a seperator.
	$sep = '<span class="sep">/</span>';

	?>

	<div class="breadcrumbs-wrapper<?php echo $additional_classes; ?>">

		<div class="breadcrumbs-inner-wrapper no-scrollbars">

			<ul class="breadcrumbs reset-list-style">

				<?php

				// No seperator before the first item.
				echo '<li><a href="' . home_url() . '">' . __( 'Hem', 'lnob' ) . '</a></li>';

				if ( is_front_page() ) {
					// On the front page, do nothing.
				} elseif ( is_404() ) {
					echo '<li>' . $sep . __( 'Error 404', 'lnob' ) . '</li>';
				} elseif ( is_tag() || is_category() || is_tax() ) {

					$taxonomy 			= get_taxonomy( $queried_object->taxonomy );
					$taxonomy_labels 	= get_taxonomy_labels( $taxonomy );

					// If we're showing post taxonomies, and a page for posts exists, link to it.
					if ( ( is_tag() || is_category() ) && $page_for_posts_id ) {
						echo '<li>' . $sep . '<a href="' . get_permalink( $page_for_posts_id ) . '">' . get_the_title( $page_for_posts_id ) . '</a></li>';
					}

					// If we're showing a tax, and that tax has a single CPT, and that CPT is public and has an archive, link to it.
					else {
						$tax_cpts = isset( $taxonomy->object_type ) ? $taxonomy->object_type : array();
						if ( count( $tax_cpts ) === 1 ) {
							$tax_cpt = get_post_type_object( $tax_cpts[0] );
							if ( $tax_cpt && $tax_cpt->public && $tax_cpt->has_archive ) {
								$tax_cpt_name = isset( $tax_cpt->labels->singular_name ) ? $tax_cpt->labels->singular_name : $tax_cpt->labels->name;
								$tax_cpt_archive_url = get_post_type_archive_link( $tax_cpt->name );
								echo '<li>' . $sep . '<a href="' . $tax_cpt_archive_url . '">' . $tax_cpt_name . '</a></li>';
							}
						}
					}

					echo '<li>' . $sep . $taxonomy_labels->singular_name . '</li>';
					echo '<li>' . $sep . '<a href="' . get_term_link( $queried_object ) . '">' . $queried_object->name . '</a></li>';
				} elseif ( is_day() ) {
					echo '<li>' . $sep . __( 'Dag', 'lnob' ) . '</li>';
					echo '<li>' . $sep . ''; the_time( get_option( 'date_format' ) ); echo'</li>';
				} elseif ( is_month() ) {
					echo '<li>' . $sep .  __( 'Månad', 'lnob' ) . '</li>';
					echo '<li>' . $sep . get_the_time( 'F Y' ) . '</li>';
				} elseif ( is_year() ) {
					echo '<li>' . $sep . __( 'År', 'lnob' ) . '</li>';
					echo '<li>' . $sep . get_the_time( 'Y' ) . '</li>';
				} elseif ( is_author() ) {
					echo '<li>' . $sep . __( 'Författare', 'lnob' ) . '</li>';
					echo '<li>' . $sep . '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author() . '</a></li>'; 
				} elseif ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] ) ) {
					echo '<li>' . $sep . __( 'Arkiv', 'lnob' ) . '</li>';
				} elseif ( is_search() ) {
					echo '<li>' . $sep . __( 'Sök', 'lnob' ) . '</li>';
					echo '<li>' . $sep . '"' . get_search_query() . '"</li>';
				} elseif ( is_archive() || is_home() ) {
					echo '<li>' . $sep . get_the_archive_title() .'</li>';
				} elseif ( is_singular() ) {

					// If the post type has a post type archive, output it.
					if ( $post_type_obj->has_archive ) {
						if ( ! isset( $post_type_archive_label ) ) {
							$post_type_archive_label = $post_type_obj->labels->name;
						}
						echo '<li>' . $sep . '<a href="' . get_post_type_archive_link( $post_type ) . '">' . $post_type_archive_label . '</a></li>';
					} elseif ( $post_type == 'attachment' ) {
						echo '<li>' . $sep . __( 'Media', 'lnob' ) . '</li>';
					} elseif ( $post_type == 'product' ) {
						$shop_id = get_option( 'woocommerce_shop_page_id' );
						if ( $shop_id ) {
							echo '<li>' . $sep . '<a href="' . get_permalink( $shop_id ) . '">' . get_the_title( $shop_id ) . '</a></li>';
						}
					}

					// Output ancestors for post types that support it.
					if ( $ancestors = get_post_ancestors( $post_id ) ) {
						$ancestors = array_reverse( $ancestors );
						foreach ( $ancestors as $ancestor_id ) {
							echo '<li>' . $sep . '<a href="' . get_permalink( $ancestor_id ) . '">' . get_the_title( $ancestor_id ) . '</a></li>';
						}
					}

					// Output link to the blog page if we're on a blog post.
					if ( $post_type == 'post' && $page_for_posts_id ) {
						echo '<li>' . $sep . '<a href="' . get_permalink( $page_for_posts_id ) . '">' . get_the_title( $page_for_posts_id ) . '</a></li>';	
					}

					echo '<li>' . $sep . '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a></li>';

				} else {

					echo '<li>' . $sep . '<a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a></li>';

				}

				?>

			</ul><!-- .breadcrumbs -->

		</div><!-- .no-scrollbars -->

	</div><!-- .breadcrumbs-wrapper -->

	<?php

}


/*	-----------------------------------------------------------------------------------------------
	GET PAGE ID FROM TEMPLATE
	Get the ID of the first occurance of a page using the specified page template.

	@param		$template_name string	Page template file name for which to retrieve the post ID.
--------------------------------------------------------------------------------------------------- */

function lnob_get_id_from_template( $template_name ) {

	// Query all pages with the specified page template.
	$pages = get_posts( array(
		'meta_key'    	=> '_wp_page_template',
		'meta_value'  	=> $template_name,
		'post_status'   => 'publish',
		'post_type'		=> 'page'
	) );

	// Get the ID of the first occurance of that page.
	if ( isset( $pages[0] ) ) {
		$id = $pages[0]->ID;
		return $id;
	}

	return false;

}


/*	-----------------------------------------------------------------------------------------------
	RETURN FACEBOOK APP ID
--------------------------------------------------------------------------------------------------- */

function lnob_get_facebook_app_id() {
	return '';
}

/* ---------------------------------------------------------------------------------------------
   CHECK FOR FRONT-END AJAX REQUEST
   Allows us to differentiate between AJAX requests done on the front-end and the back-end (wp-admin).
   Kudos to Stanislav: https://snippets.khromov.se/determine-if-wordpress-ajax-request-is-a-backend-of-frontend-request/
------------------------------------------------------------------------------------------------ */

function lnob_is_request_front_end_ajax() {

	// If we're not doing AJAX at all, return false.
	if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) return false;

	$script_filename = isset( $_SERVER['SCRIPT_FILENAME'] ) ? $_SERVER['SCRIPT_FILENAME'] : '';

	// If we are, check the referer of the request.
	$ref = '';
	if ( ! empty( $_REQUEST['_wp_http_referer'] ) )
		$ref = wp_unslash( $_REQUEST['_wp_http_referer'] );
	elseif ( ! empty( $_SERVER['HTTP_REFERER'] ) )
		$ref = wp_unslash( $_SERVER['HTTP_REFERER'] );

	// If the referer does not include the admin url, it's likely a front-end request.
	if ( ( ( strpos( $ref, admin_url() ) === false ) && ( basename( $script_filename ) === 'admin-ajax.php' ) ) ) {
		return true;
	}

	// If not, return false.
	return false;
}

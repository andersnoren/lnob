<?php


/*	-----------------------------------------------------------------------------------------------
	HELPER VARIABLES
	Define global variables for use in the theme
--------------------------------------------------------------------------------------------------- */

define( 'LNOB_STYLESHEET_DIR', get_stylesheet_directory() );
define( 'LNOB_STYLESHEET_DIR_URI', get_stylesheet_directory_uri() );
define( 'LNOB_TEMPLATE_DIR', get_template_directory() );
define( 'LNOB_TEMPLATE_DIR_URI', get_template_directory_uri() );


/*	-----------------------------------------------------------------------------------------------
	THEME SUPPORTS
	Default setup, some features excluded
--------------------------------------------------------------------------------------------------- */

function lnob_theme_support()  {

	// Automatic feed links.
	add_theme_support( 'automatic-feed-links' );

	// Featured images.
	add_theme_support( 'post-thumbnails' );

	// Featured image size.
	set_post_thumbnail_size( 1440, 1440, false ); // Change to theme thumbnail size

	// Add custom image sizes.
	// add_image_size( 'lnob_fullscreen', 1920, 1440, false );

	// Set content width.
	global $content_width;
	$content_width = 580; // Change to theme value

	// HTML5 semantic markup.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Title tag.
	add_theme_support( 'title-tag' );

	// Responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Editor styles.
	add_theme_support( 'editor-styles' );

	add_editor_style( array( 'assets/css/blocks.min.css', 'assets/css/editor-style.min.css' ) );

	// I18n.
	load_theme_textdomain( 'lnob', LNOB_TEMPLATE_DIR . '/language' );

	// Alignwide and alignfull classes in the block editor.
	add_theme_support( 'align-wide' );
	
}
add_action( 'after_setup_theme', 'lnob_theme_support' );


/*	-----------------------------------------------------------------------------------------------
	INCLUDE REQUIRED FILES
--------------------------------------------------------------------------------------------------- */

// Custom template tags for this theme.
require LNOB_TEMPLATE_DIR . '/inc/template-tags.php';


/*	-----------------------------------------------------------------------------------------------
	REGISTER STYLES
	Register and enqueue CSS
--------------------------------------------------------------------------------------------------- */

function lnob_register_styles() {

	// wp_register_style( 'lnob-typekit', 'https://use.typekit.net/XXXXXXX.css', array(), '1.0', 'all' );

	wp_register_style( 'lnob-blocks', LNOB_TEMPLATE_DIR_URI . '/assets/css/blocks.min.css', array(), '1.0', 'all' );

	$css_dependencies = array();
	
	// When there are blocks to show, load the blocks styles.
	if ( is_singular() && get_the_content() ) {
		$css_dependencies[] = 'lnob-blocks';
	}
	
	wp_enqueue_style( 'lnob-style', LNOB_TEMPLATE_DIR_URI . '/style.min.css', $css_dependencies, filemtime( LNOB_TEMPLATE_DIR . '/style.min.css' ) );

}
add_action( 'wp_enqueue_scripts', 'lnob_register_styles' );


/*	-----------------------------------------------------------------------------------------------
	REGISTER SCRIPTS
	Register and enqueue JavaScript.
--------------------------------------------------------------------------------------------------- */

function lnob_register_scripts() {

	// wp_register_script( 'lnob-name', LNOB_TEMPLATE_DIR_URI . '/assets/javascript/file.js', array(), '1.0' );
	
	$js_dependencies = array( 'jquery' );

	wp_enqueue_script( 'lnob-construct', LNOB_TEMPLATE_DIR_URI . '/assets/javascript/construct.min.js', $js_dependencies, filemtime( LNOB_TEMPLATE_DIR . '/assets/javascript/construct.min.js' ) );

	// Setup AJAX.
	wp_localize_script( 'lnob-construct', 'lnobData', array(
		'ajaxURL' 	=> esc_url( admin_url( 'admin-ajax.php' ) )
	) );

}
add_action( 'wp_enqueue_scripts', 'lnob_register_scripts' );


/*	-----------------------------------------------------------------------------------------------
	BLOCK EDITOR ASSETS
	Register styles and scripts for the block editor. Styles loaded here do not get the 
	.editor-styles-wrapper targeting prepended automatically.
--------------------------------------------------------------------------------------------------- */

function lnob_block_editor_styles() {

	/* 
	 * Set the HTML value of the block editor view to match the front-end (1rem = 10px),
	 * enabling the use of REM values in all CSS files.
	 */

	wp_register_style( 'lnob-block-editor-dummy-handle', false );
    wp_enqueue_style( 'lnob-block-editor-dummy-handle' );
	wp_add_inline_style( 'lnob-block-editor-dummy-handle', 'html { font-size: 62.5% !important; }' );

}
add_action( 'enqueue_block_editor_assets', 'lnob_block_editor_styles' );


/*	-----------------------------------------------------------------------------------------------
	MODFIY LOADING OF SCRIPTS
--------------------------------------------------------------------------------------------------- */

function lnob_script_loader_tag( $tag, $handle, $src ) {

	if ( is_admin() ) return $tag;
	
	$scripts = array( 'lnob-construct' );

	foreach ( $scripts as $script ) {
		if ( $script == $handle ) {
			
			if ( false === stripos( $tag, 'async' ) ) {
				$tag = str_replace( ' src', ' async="async" src', $tag );
			}
			
		}
	}
	
	return $tag;
	
}
add_filter( 'script_loader_tag', 'lnob_script_loader_tag', 10, 3 );


/*	-----------------------------------------------------------------------------------------------
	REMOVE EMOJIS
--------------------------------------------------------------------------------------------------- */

remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
remove_action( 'admin_print_styles', 'print_emoji_styles' );


/*	-----------------------------------------------------------------------------------------------
   	REMOVE INLINE WIDTHS FROM CAPTIONS
--------------------------------------------------------------------------------------------------- */

add_filter( 'img_caption_shortcode_width', '__return_false' );


/*	-----------------------------------------------------------------------------------------------
	MENUS
	Register navigational menus (wp_nav_menu)
--------------------------------------------------------------------------------------------------- */

function lnob_menus() {

	$locations = array(
		'main' 			=> __( 'Huvudmeny', 'lnob' ),
		'mobile' 		=> __( 'Mobilmeny', 'lnob' ),
	);
	
	register_nav_menus( $locations );

}
add_action( 'init', 'lnob_menus' );


/*	-----------------------------------------------------------------------------------------------
	ADD SCHEMA.ORG MARKUP TO MENUS
--------------------------------------------------------------------------------------------------- */

function lnob_add_menu_attributes( $atts, $item, $args ) {

	$atts['itemprop'] = 'url';

	return $atts;

}
add_filter( 'nav_menu_link_attributes', 'lnob_add_menu_attributes', 10, 3 );


/*	-----------------------------------------------------------------------------------------------
	BODY CLASSES
	Conditional addition of classes to the body element.
--------------------------------------------------------------------------------------------------- */

function lnob_body_classes( $classes ) {

	global $post;

	// Add class for whether there is a post thumbnail.
	if ( is_singular() ) {
		$classes[] = has_post_thumbnail( $post->ID ) ? 'has-post-thumbnail' : 'no-post-thumbnail';
	}

	// Add post type class to archive.
	if ( is_post_type_archive() ) {
		$classes[] = 'archive-' . get_post_type();
	}

	// Add class if we're singular.
	if ( is_singular() ) {
		$classes[] = 'singular';
	}

	// Shortened page template class (turns folder/template-name.php into template-name).
	if ( is_page_template() ) {
		$classes[] = basename( get_page_template_slug(), '.php' );
	}

	return $classes;
	
}
add_filter( 'body_class', 'lnob_body_classes' );


/*	-----------------------------------------------------------------------------------------------
	REDIRECTS
	Conditional redirects on the site.
--------------------------------------------------------------------------------------------------- */

function lnob_redirects() {

	$request_uri = $_SERVER['REQUEST_URI'];

	$user_id 	= get_current_user_id();
	$post_id 	= get_the_ID();
	$post_type 	= get_post_type( $post_id );

	// Replace with a real conditional
	if ( true === false ) {
		$redirect_url = home_url();
	}

	// Redirect if a redirect URL is set
	if ( isset( $redirect_url ) ) {
		wp_redirect( $redirect_url );
		exit;
	}

}
add_action( 'template_redirect', 'lnob_redirects' );


/*	-----------------------------------------------------------------------------------------------
	NO-JS CLASS
	If we're missing JavaScript support, the HTML element will have a no-js class.
--------------------------------------------------------------------------------------------------- */

function lnob_no_js_class() {

	?>
	<script>document.documentElement.className = document.documentElement.className.replace( 'no-js', 'js' );</script>
	<?php

}
add_action( 'wp_head', 'lnob_no_js_class' );


/*	-----------------------------------------------------------------------------------------------
	REMOVE THE ARCHIVE TITLE PREFIX
	Remove the prefix WordPress prepends to the archive title, like "Category: ", "Tag: ", etc.
--------------------------------------------------------------------------------------------------- */

add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );


/*	-----------------------------------------------------------------------------------------------
	FILTER ARCHIVE TITLE
	Any changes to the archive title for different CPTs (like custom descriptions set in ACF)
	should be made in this function, since they then carry over to breadcrumbs, title/meta tag, etc.

	@param	$title string	The initial title.
--------------------------------------------------------------------------------------------------- */

function lnob_filter_archive_title( $title ) {

	// No title on the front page.
	if ( is_front_page() ) {
		$title = '';

	// Use the blog page title on home.
	} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
		$title = get_the_title( get_option( 'page_for_posts' ) );

	// Search: Show the search query.
	} else if ( is_search() ) {
		// Translators: %s = The search query
		$title = sprintf( _x( 'Sök: %s', '%s = The search query', 'lnob' ), '&ldquo;' . get_search_query() . '&rdquo;' );
	}

	return $title;
	
}
add_filter( 'get_the_archive_title', 'lnob_filter_archive_title' );


/*	-----------------------------------------------------------------------------------------------
	FILTER ARCHIVE DESCRIPTION
	Any changes to the archive description for different CPTs (like custom descriptions set in 
	ACF) should be made in this function, since they then carry over to breadcrumbs, meta tags etc.

	@param	$description string		The initial description.
--------------------------------------------------------------------------------------------------- */

function lnob_filter_archive_description( $description ) {

	// Use the blog page intro_text on home.
	if ( is_home() && get_option( 'page_for_posts' ) ) {
		$description = get_field( 'intro_text', get_option( 'page_for_posts' ) );

	// Search: Show the search query.
	} elseif ( is_search() ) {
		global $wp_query;
		if ( $wp_query->found_posts ) {
			// Translators: %s = Number of results
			$description = sprintf( _nx( 'Vi hittade %s resultat för din sökning.', 'Vi hittade %s resultat för din sökning.', $wp_query->found_posts, '%s = Antal resultat', 'lnob' ), $wp_query->found_posts );
		} else {
			$description = __( 'Vi hittade inte några resultat för din sökning.', 'lnob' );
		}
	}

	return $description;
	
}
add_filter( 'get_the_archive_description', 'lnob_filter_archive_description' );


/*	-----------------------------------------------------------------------------------------------
	OG/META TAG FUNCTION
	Add meta tags, Facebook OG tags and Twitter Card tags to the head of the current page.
--------------------------------------------------------------------------------------------------- */

function lnob_meta_tags() {

	global $post;

	$image_url = '';

	// Fallback image.
	$image_fallback = LNOB_TEMPLATE_DIR_URI . '/screenshot.png';
	$image_fallback_width = 1200;
	$image_fallback_height = 900;

	// Front Page (when it's showing posts).
	if ( get_option( 'show_on_front' ) == 'posts' && is_home() ) {

		$title = get_bloginfo( 'name' );
		$excerpt = wp_strip_all_tags( get_bloginfo( 'description' ) );
		$type = 'website';
		$url = home_url();

	// Front Page (when it's showing a page).
	} elseif ( is_front_page() ) {

		$title = get_bloginfo( 'name' );
		$excerpt = wp_strip_all_tags( get_bloginfo( 'description' ) );
		$type = 'website';
		$url = home_url();
		if ( $post && has_post_thumbnail( $post->ID ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' );
			if ( $image ) {
				$image_url 		= $image[0];
				$image_width 	= $image[1];
				$image_height 	= $image[2];
			}
		}

	// Singular.
	} elseif ( is_singular() ) {
		
		$title = wp_strip_all_tags( wp_get_document_title( $post->ID ) );

		if ( function_exists( 'get_field' ) && get_field( 'intro_text', $post->ID ) ) {
			$excerpt = wp_strip_all_tags( get_field( 'intro_text', $post->ID ) );	
		} else {
			$excerpt = wp_strip_all_tags( get_the_excerpt( $post->ID ) );
		}

		$type = 'article';
		$url = get_permalink( $post->ID );
		if ( $post && has_post_thumbnail( $post->ID ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' );
			if ( $image ) {
				$image_url 		= $image[0];
				$image_width 	= $image[1];
				$image_height 	= $image[2];
			}
		}

	} elseif ( is_home() || is_archive() ) {

		$title = wp_strip_all_tags( get_the_archive_title() );
		$excerpt = wp_strip_all_tags( get_the_archive_description() );
		$type = 'website';
		$url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	}

	$facebook_app_id = lnob_get_facebook_app_id();

	// Default image to fallback.
	if ( ! $image_url ) {
		$image_url 		= $image_fallback;
		$image_width 	= $image_fallback_width;
		$image_height 	= $image_fallback_height;
	}

	// Output the values.
	
	?>

	<meta name="twitter:card" content="summary" />

	<?php if ( $facebook_app_id ) : ?>
		<meta property="fb:app_id" content="<?php echo esc_attr( $facebook_app_id ); ?>" />
	<?php endif; ?>

	<?php if ( isset( $title ) && $title ) : ?>
		<meta property="og:title" content="<?php echo esc_attr( $title ); ?>"/>
		<?php // Keep the Twitter title within the max limit
		$twitter_title = ( strlen( $title ) > 67 ? substr( $title, 0, 67 ) . '...' : $title ); ?>
		<meta name="twitter:title" content="<?php echo esc_attr( $twitter_title ); ?>" />
	<?php endif; ?>

	<?php if ( isset( $excerpt ) && $excerpt ) : ?>
		<meta name="description" content="<?php echo esc_attr( $excerpt ); ?>"/>
		<meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>"/>
		<?php // Keep the Twitter description within the max limit
		$twitter_excerpt = ( strlen( $excerpt ) > 197 ? substr( $excerpt, 0, 197 ) . '...' : $excerpt ); ?>
		<meta name="twitter:description" content="<?php echo esc_attr( $twitter_excerpt ); ?>" />
	<?php endif; ?>

	<?php if ( isset( $type ) && $type ) : ?>
		<meta property="og:type" content="<?php echo esc_attr( $type ); ?>"/>
	<?php endif; ?>

	<?php if ( isset( $url ) && $url ) : ?>
		<meta property="og:url" content="<?php echo esc_url( $url ); ?>"/>
		<meta name="twitter:url" content="<?php echo esc_url( $url ); ?>" />
	<?php endif; ?>

	<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/>

	<?php if ( isset( $image_url ) && $image_url ) : ?>
		<meta property="og:image" content="<?php echo esc_url( $image_url ); ?>"/>
		<meta property="og:image:width" content="<?php echo esc_attr( $image_width ); ?>"/>
		<meta property="og:image:height" content="<?php echo esc_attr( $image_height ); ?>"/>
		<meta name="twitter:image" content="<?php echo $image_url; ?>"/>
	<?php endif;

}
add_action( 'wp_head', 'lnob_meta_tags', 5 );


/*	-----------------------------------------------------------------------------------------------
	AJAX LOAD MORE
	Called in construct.js when the user has clicked the load more button.
--------------------------------------------------------------------------------------------------- */

function lnob_ajax_load_more() {

	$query_args = json_decode( wp_unslash( $_POST['json_data'] ), true );

	$ajax_query = new WP_Query( $query_args );

	// Get custom arguments passed in query_args.
	$preview 		= isset( $query_args['lnob_preview'] ) ? $query_args['lnob_preview'] : 'inc/parts/preview';
	$grid_classes 	= isset( $query_args['lnob_grid_classes'] ) ? $query_args['lnob_grid_classes'] : true;

	if ( $ajax_query->have_posts() ) :
		while ( $ajax_query->have_posts() ) : 
			$ajax_query->the_post();

			if ( $grid_classes ) echo '<div class="col">';

			include( locate_template( $preview . '.php' ) );

			if ( $grid_classes ) echo '</div>';

		endwhile;
	endif;

	wp_die();

}
add_action( 'wp_ajax_nopriv_lnob_ajax_load_more', 'lnob_ajax_load_more' );
add_action( 'wp_ajax_lnob_ajax_load_more', 'lnob_ajax_load_more' );


/*	-----------------------------------------------------------------------------------------------
	PRE_GET_POSTS
	Conditional modifications of queries.
--------------------------------------------------------------------------------------------------- */

function lnob_pre_get_posts( $query ) {

	// Only run on the front-end.
	if ( lnob_is_request_front_end_ajax() || ! is_admin() ) {

		// Make modifications if conditionals are true.
		if ( $query->is_main_query() && true == false ) {
			$query->set( 'posts_per_page', -1 );
		}

	}

}
// add_action( 'pre_get_posts', 'lnob_pre_get_posts' );


/* ---------------------------------------------------------------------------------------------
	FILTER SEARCH FORM
	Filter the search form to update the placeholder of the search field.
------------------------------------------------------------------------------------------------ */

function lnob_filter_get_search_form( $form, $args ) {

	$form = preg_replace( '/\splaceholder=("|\').*?("|\')/i', 'placeholder="' . __( 'Sök efter&hellip;', 'lnob' ) . '"', $form );

	return $form;

}
add_filter( 'get_search_form', 'lnob_filter_get_search_form', 10, 2 );


/* ---------------------------------------------------------------------------------------------
   SPECIFY BLOCK EDITOR SUPPORT
------------------------------------------------------------------------------------------------ */

function lnob_add_block_editor_features() {

	/* Block Editor Font Sizes ----------- */

	add_theme_support( 'editor-font-sizes', array(
		array(
			'name'      => __( 'Liten', 'lnob' ),
			'shortName' => __( 'S', 'lnob' ),
			'size'      => 16,
			'slug'      => 'small',
		),
		array(
			'name'      => __( 'Normal', 'lnob' ),
			'shortName' => __( 'M', 'lnob' ),
			'size'      => 18,
			'slug'      => 'normal',
		),
		array(
			'name'      => __( 'Stor', 'lnob' ),
			'shortName' => __( 'L', 'lnob' ),
			'size'      => 24,
			'slug'      => 'large',
		),
		array(
			'name'      => __( 'Extra stor', 'lnob' ),
			'shortName' => __( 'XL', 'lnob' ),
			'size'      => 32,
			'slug'      => 'larger',
		),
	) );

	/* Block Editor Palette -------------- */

	add_theme_support( 'editor-color-palette', array(
		array(
			'name' 	=> __( 'Accent', 'lnob' ),
			'slug' 	=> 'accent',
			'color' => '#BF75AE',
		),
		array(
			'name' 	=> __( 'Svart', 'lnob' ),
			'slug' 	=> 'black',
			'color' => '#151619',
		),
		array(
			'name' 	=> __( 'Grå', 'lnob' ),
			'slug' 	=> 'gray',
			'color' => '#666666',
		),
		array(
			'name' 	=> __( 'Ljusgrå', 'lnob' ),
			'slug' 	=> 'light-gray',
			'color' => '#dddddd',
		),
		array(
			'name' 	=> __( 'Mest ljusgrå', 'lnob' ),
			'slug' 	=> 'lightest-gray',
			'color' => '#f1f1f1',
		),
		array(
			'name' 	=> __( 'Vit', 'lnob' ),
			'slug' 	=> 'white',
			'color' => '#ffffff',
		),
	) );

}
add_action( 'after_setup_theme', 'lnob_add_block_editor_features' );

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

	// Add custom image sizes.
	add_image_size( 'lnob_fullscreen', 1920, 1440, false );

	// Set content width.
	global $content_width;
	$content_width = 600;

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

	wp_register_style( 'lnob-blocks', LNOB_TEMPLATE_DIR_URI . '/assets/css/blocks.min.css', array(), '1.0', 'all' );

	$css_dependencies = array( 'lnob-blocks' );

	wp_enqueue_style( 'lnob-style', LNOB_TEMPLATE_DIR_URI . '/style.min.css', $css_dependencies, filemtime( LNOB_TEMPLATE_DIR . '/style.min.css' ) );

}
add_action( 'wp_enqueue_scripts', 'lnob_register_styles' );


/*	-----------------------------------------------------------------------------------------------
	REGISTER SCRIPTS
	Register and enqueue JavaScript.
--------------------------------------------------------------------------------------------------- */

function lnob_register_scripts() {
	
	$js_dependencies = array( 'jquery' );

	wp_enqueue_script( 'lnob-construct', LNOB_TEMPLATE_DIR_URI . '/assets/javascript/construct.min.js', $js_dependencies, filemtime( LNOB_TEMPLATE_DIR . '/assets/javascript/construct.min.js' ) );

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
	ADD SCHEMA.ORG MARKUP TO MENUS
--------------------------------------------------------------------------------------------------- */

function lnob_add_menu_attributes( $atts, $item, $args ) {

	$atts['itemprop'] = 'url';

	return $atts;

}
add_filter( 'nav_menu_link_attributes', 'lnob_add_menu_attributes', 10, 3 );


/*	-----------------------------------------------------------------------------------------------
	REDIRECTS
	Conditional redirects on the site.
--------------------------------------------------------------------------------------------------- */

function lnob_redirects() {

	global $post;
	$request_uri = $_SERVER['REQUEST_URI'];

	// Redirect visits to GGs to the right section.
	if ( is_singular( 'lnob_global_goal' ) ) {
		$redirect_url = home_url() . '#gg-' . basename( $request_uri );

	// Redirect all other visitors to the front page.
	} else if ( ! is_front_page() ) {
		$redirect_url = home_url();
	}

	// Redirect if a redirect URL is set.
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


/* ---------------------------------------------------------------------------------------------
   SPECIFY BLOCK EDITOR SUPPORT
------------------------------------------------------------------------------------------------ */

function lnob_add_block_editor_features() {


	/* Block Styles -------------------------- */

	// Button: Icons.
	register_block_style( 'core/button', array(
		'label' => __( 'Ikon: Svart pil ned??t', 'lnob' ),
		'name'  => 'button-has-icon-arrow-down-black',
	) );

	register_block_style( 'core/button', array(
		'label' => __( 'Ikon: Vit pil ned??t', 'lnob' ),
		'name'  => 'button-has-icon-arrow-down-white',
	) );

	// List: ForumCiv List.
	register_block_style( 'core/list', array(
		'label' => __( 'ForumCiv-punkt', 'lnob' ),
		'name'  => 'forumciv-bullet',
	) );


	/* Block Patterns ------------------------ */

	remove_theme_support( 'core-block-patterns' );

	register_block_pattern_category( 'lnob', array( 
		'label' => esc_html__( 'Leave No One Behind', 'lnob' ) 
	) );
	
	// Register block patterns.
	$block_patterns = array(
		'lnob/columns-heading-paragraph' => array(
			'title'			=> esc_html__( 'Kolumner med rubrik och text', 'lnob' ),
			'description'	=> esc_html__( 'Ett brett kolumner-block med en rubrik och ett textstycke.', 'lnob' ),
			'content'		=> lnob_get_block_pattern_markup( 'inc/block-patterns/columns-heading-paragraph.php' ),
			'categories'	=> array( 'lnob' ),
			'viewportWidth'	=> 1440,
		),
		'lnob/columns-numbers' => array(
			'title'			=> esc_html__( 'Kolumner med siffror', 'lnob' ),
			'description'	=> esc_html__( 'Ett brett kolumner-block med tv?? siffror-block med bakgrundsf??rg.', 'lnob' ),
			'content'		=> lnob_get_block_pattern_markup( 'inc/block-patterns/columns-numbers.php' ),
			'categories'	=> array( 'lnob' ),
			'viewportWidth'	=> 1440,
		),
		'lnob/columns-pullquote-columns' => array(
			'title'			=> esc_html__( 'H??gerjusterade textkolumner med stort citat', 'lnob' ),
			'description'	=> esc_html__( 'En h??gerjusterad textkolumn, f??ljd av ett stort citat, f??ljd av en till h??gerjusterad textkolumn.', 'lnob' ),
			'content'		=> lnob_get_block_pattern_markup( 'inc/block-patterns/columns-pullquote-columns.php' ),
			'categories'	=> array( 'lnob' ),
			'viewportWidth'	=> 1440,
		),
		'lnob/group-heading-paragraph' => array(
			'title'			=> esc_html__( 'F??rgad grupp med rubrik och text', 'lnob' ),
			'description'	=> esc_html__( 'En bred grupp med svart bakgrundsf??rg, rubrik och text.', 'lnob' ),
			'content'		=> lnob_get_block_pattern_markup( 'inc/block-patterns/group-heading-paragraph.php' ),
			'categories'	=> array( 'lnob' ),
			'viewportWidth'	=> 1440,
		),
		'lnob/list-columns-heading-text' => array(
			'title'			=> esc_html__( 'Lista i tv?? kolumner med rubrik och text', 'lnob' ),
			'description'	=> esc_html__( 'Lista med ForumCiv-symbolen som punkt, med rubrik och text i varje rad i listan.', 'lnob' ),
			'content'		=> lnob_get_block_pattern_markup( 'inc/block-patterns/list-columns-heading-text.php' ),
			'categories'	=> array( 'lnob' ),
			'viewportWidth'	=> 1440,
		),
		'lnob/list-squircle' => array(
			'title'			=> esc_html__( 'Lista med ForumCiv-symbolen', 'lnob' ),
			'description'	=> esc_html__( 'Enkel lista med ren text och ForumCiv-symbolen som punkt.', 'lnob' ),
			'content'		=> lnob_get_block_pattern_markup( 'inc/block-patterns/list-squircle.php' ),
			'categories'	=> array( 'lnob' ),
			'viewportWidth'	=> 1440,
		),
	);

	foreach ( $block_patterns as $name => $data ) {
		register_block_pattern( $name, $data );
	}


	/* Editor Font Sizes --------------------- */

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
			'size'      => 20,
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
	

	/* Editor Palette ------------------------ */

	add_theme_support( 'disable-custom-colors' );	
	add_theme_support( 'disable-custom-gradients' );

	add_theme_support( 'editor-color-palette', array(
		array(
			'name' 	=> __( 'Gult', 'lnob' ),
			'slug' 	=> 'yellow',
			'color' => '#ffcc00',
		),
		array(
			'name' 	=> __( 'Magenta', 'lnob' ),
			'slug' 	=> 'magenta',
			'color' => '#e20c7b',
		),
		array(
			'name' 	=> __( 'Lavender', 'lnob' ),
			'slug' 	=> 'lavender',
			'color' => '#71628f',
		),
		array(
			'name' 	=> __( 'Svart', 'lnob' ),
			'slug' 	=> 'black',
			'color' => '#000',
		),
		array(
			'name' 	=> __( 'Gr??', 'lnob' ),
			'slug' 	=> 'gray-dark',
			'color' => '#d2d2d2',
		),
		array(
			'name' 	=> __( 'Ljusgr??', 'lnob' ),
			'slug' 	=> 'gray-light',
			'color' => '#f2f2f2',
		),
		array(
			'name' 	=> __( 'Vit', 'lnob' ),
			'slug' 	=> 'white',
			'color' => '#ffffff',
		),
		array( 'name' => __( 'Globalt m??l 1', 'lnob' ), 'slug' => 'gg-1', 'color' => '#E5243B' ),
		array( 'name' => __( 'Globalt m??l 2', 'lnob' ), 'slug' => 'gg-2', 'color' => '#DDA63A' ),
		array( 'name' => __( 'Globalt m??l 3', 'lnob' ), 'slug' => 'gg-3', 'color' => '#4C9F38' ),
		array( 'name' => __( 'Globalt m??l 4', 'lnob' ), 'slug' => 'gg-4', 'color' => '#C5192D' ),
		array( 'name' => __( 'Globalt m??l 5', 'lnob' ), 'slug' => 'gg-5', 'color' => '#FF3A21' ),
		array( 'name' => __( 'Globalt m??l 6', 'lnob' ), 'slug' => 'gg-6', 'color' => '#26BDE2' ),
		array( 'name' => __( 'Globalt m??l 7', 'lnob' ), 'slug' => 'gg-7', 'color' => '#FCC30B' ),
		array( 'name' => __( 'Globalt m??l 8', 'lnob' ), 'slug' => 'gg-8', 'color' => '#A21942' ),
		array( 'name' => __( 'Globalt m??l 9', 'lnob' ), 'slug' => 'gg-9', 'color' => '#FD6925' ),
		array( 'name' => __( 'Globalt m??l 10', 'lnob' ), 'slug' => 'gg-10', 'color' => '#DD1367' ),
		array( 'name' => __( 'Globalt m??l 11', 'lnob' ), 'slug' => 'gg-11', 'color' => '#FD9D24' ),
		array( 'name' => __( 'Globalt m??l 12', 'lnob' ), 'slug' => 'gg-12', 'color' => '#BF8B2E' ),
		array( 'name' => __( 'Globalt m??l 13', 'lnob' ), 'slug' => 'gg-13', 'color' => '#3F7E44' ),
		array( 'name' => __( 'Globalt m??l 14', 'lnob' ), 'slug' => 'gg-14', 'color' => '#0A97D9' ),
		array( 'name' => __( 'Globalt m??l 15', 'lnob' ), 'slug' => 'gg-15', 'color' => '#56C02B' ),
		array( 'name' => __( 'Globalt m??l 16', 'lnob' ), 'slug' => 'gg-16', 'color' => '#00689D' ),
		array( 'name' => __( 'Globalt m??l 17', 'lnob' ), 'slug' => 'gg-17', 'color' => '#19486A' ),
	) );

}
add_action( 'after_setup_theme', 'lnob_add_block_editor_features' );


/* ---------------------------------------------------------------------------------------------
   GET BLOCK PATTERN MARKUP
------------------------------------------------------------------------------------------------ */

function lnob_get_block_pattern_markup( $path ) {

	if ( ! locate_template( $path ) ) return;

	ob_start();
	include( locate_template( $path ) );
	return ob_get_clean();

}


/* ---------------------------------------------------------------------------------------------
   COMMENT OUT DEFAULT FOOTNOTES
------------------------------------------------------------------------------------------------ */

function lnob_remove_footnotes( $content ) {

	global $post;

	// Comment out default footnotes.
	if ( strpos( $content, '<ol class="footnotes">' ) ) {

		// Comment out the footnotes section in the bottom.
		preg_match( '#<ol class="footnotes">(.*?)</ol>#', $content, $footnotes );
		$footnotes_commented_out = '<!--' . $footnotes[0] . '-->';
		$content = preg_replace( '#<ol class="footnotes">(.*?)</ol>#', $footnotes_commented_out, $content );

		// Add a class to the footnote links, disabling hash scroll.
		$content = str_replace( 'footnote-identifier-link', 'footnote-identifier-link disable-hash-scroll', $content );
	}

	// Add animation classes to all alignwide and alignfull elements.
	$animation_class = 'do-spot spot-fade-up';
	$content = str_replace( 
		array( 'alignwide', 'alignfull' ), 
		array( 'alignwide ' . $animation_class, 'alignfull ' . $animation_class ), 
	$content );

	return $content;

}
add_filter( 'the_content', 'lnob_remove_footnotes', 12 );


/* ---------------------------------------------------------------------------------------------
   GET FOOTNOTES FOR A POST ID
------------------------------------------------------------------------------------------------ */

function lnob_get_footnotes( $post_id ) {

	$content = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );

	$footnotes = false;

	if ( strpos( $content, '<ol class="footnotes">' ) ) {
		preg_match( '#<ol class="footnotes">(.*?)</ol>#', $content, $footnotes );
	}

	return $footnotes ? $footnotes[0] : false;

}


/* ---------------------------------------------------------------------------------------------
   GET FORMATTED FOOTNOTES FOR A POST ID
   Used by Introduktion, Vilka syns inte? och Rekommendationer for outputting the footnotes button and box.
------------------------------------------------------------------------------------------------ */

function lnob_get_formatted_footnotes( $post_id ) {

	$footnotes = lnob_get_footnotes( $post_id );

	ob_start();

	if ( ! $footnotes ) return '';
	?>

	<div class="footnotes-button-wrapper d-flex d-no-js-none">
		<button class="footnotes-button faux-button bg-gray-dark c-black">
			<div class="icon-text">
				<div class="icon"><?php lnob_the_svg( 'book', 24, 24 ); ?></div>
				<div class="text">
					<span class="show-inactive"><?php _e( 'Visa k??llor', 'lnob' ); ?></span>
					<span class="show-active"><?php _e( 'D??lj k??llor', 'lnob' ); ?></span>
				</div>
			</div><!-- .icon-text -->
		</button>
	</div><!-- .footnotes-button-wrapper -->

	<div class="footnotes-box-wrapper pu-24 pu-t-48 d-none d-no-js-block d-active-block">
		<div class="footnotes-box bg-gray-light contain-margins p-24 p-t-48">
			<?php echo $footnotes; ?>
		</div><!-- .footnotes-box -->
	</div><!-- .footnotes-box-wrapper -->

	<?php

	return ob_get_clean();

}

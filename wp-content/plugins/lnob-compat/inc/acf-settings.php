<?php

/*	-----------------------------------------------------------------------------------------------
	DEFINE ACF SAVE POINT
	This tells ACF where to export the JSON files when a field group is updated
--------------------------------------------------------------------------------------------------- */

function lnob_compat_acf_json_save_point( $path ) {
	return LNOB_COMPAT_DIR . '/assets/acf-json';
}
add_filter( 'acf/settings/save_json', 'lnob_compat_acf_json_save_point' );


/*	-----------------------------------------------------------------------------------------------
	DEFINE ACF LOAD POINT
	This tells ACF where to load JSON files for field groups
--------------------------------------------------------------------------------------------------- */

function lnob_compat_acf_json_load_point( $paths ) {
	$paths[] = LNOB_COMPAT_DIR . '/assets/acf-json';

	return $paths;
}
add_filter( 'acf/settings/load_json', 'lnob_compat_acf_json_load_point' );


/*	-----------------------------------------------------------------------------------------------
	REGISTER BLOCK CATEGORY
	Register a custom block category for Briska.
--------------------------------------------------------------------------------------------------- */

function lnob_register_block_categories( $categories ) {

    return array_merge( $categories, array( array(
		'slug'  => 'lnob',
		'title' => __( 'Leave No One Behind', 'lnob-compat' ),
	) ) );

}
add_action( 'block_categories_all', 'lnob_register_block_categories', 10, 2 );


/*	-----------------------------------------------------------------------------------------------
	REGISTER ACF BLOCKS
	Register blocks for ACF.
--------------------------------------------------------------------------------------------------- */

function lnob_compat_register_acf_blocks() {

	// Block: Recommendation
	acf_register_block_type( array(
		'name'              => 'lnob-recommendation',
		'title'             => __( 'Rekommendation', 'lnob-compat' ),
		'description'       => __( 'En ruta som visar en rekommendationstext med delningslänkar.', 'lnob-compat' ),
		'render_template'   => get_theme_root() . '/lnob/inc/blocks/recommendation.php',
		'mode' 				=> 'edit',
		'align'				=> '',
		'category'          => 'lnob',
		'icon'              => 'star-filled',
		'keywords'          => array( 'lnob', 'rekommendation' ),
	) );

	// Block: Numbers
	acf_register_block_type( array(
		'name'              => 'lnob-numbers',
		'title'             => __( 'Siffror', 'lnob-compat' ),
		'description'       => __( 'En ruta som visar siffror med beskrivande text och delningslänkar.', 'lnob-compat' ),
		'render_template'   => get_theme_root() . '/lnob/inc/blocks/numbers.php',
		'mode' 				=> 'edit',
		'align'				=> '',
		'category'          => 'lnob',
		'icon'              => '<svg fill="none" height="161" viewBox="0 0 217 161" width="217" xmlns="http://www.w3.org/2000/svg"><g fill="#000"><path d="m24.6852 2.55997h20.93v155.94003h-26.22v-119.37h-.23l-18.400044 19.09h-.23v-27.37z"/><path d="m62.1428 158.5v-28.75c0-7.513 1.4567-14.26 4.37-20.24s7.9734-12.8034 15.18-20.47l14.72-15.64c4.1402-4.2934 6.7462-8.1267 7.8202-11.5 1.226-3.5267 1.84-8.6634 1.84-15.41v-12.88c0-7.82-3.144-11.73-9.4302-11.73-6.7467 0-10.12 4.0633-10.12 12.19v19.55h-23.92v-18.86c0-11.04 2.9133-19.4734 8.74-25.30002 5.98-5.82667 14.6433-8.740009 25.99-8.740009 11.6532 0 20.2402 2.683339 25.7602 8.050009 5.673 5.36662 8.51 13.64662 8.51 24.84002v15.18c0 8.1266-1.15 14.95-3.45 20.47-2.147 5.52-6.517 11.8066-13.11 18.86l-16.7902 17.71c-2.76 3.067-4.7533 5.367-5.98 6.9-1.2266 1.533-2.3767 3.833-3.45 6.9-1.0733 2.913-1.61 6.287-1.61 10.12v7.36h43.0102v21.39z"/><path d="m180.546.719971c23.153 0 34.73 11.883329 34.73 35.650029v13.57c0 13.0333-5.98 21.4666-17.94 25.3 12.88 2.9133 19.32 11.73 19.32 26.45v23c0 11.193-3.143 19.933-9.43 26.22-6.133 6.287-15.64 9.43-28.52 9.43-24.073 0-36.11-12.957-36.11-38.87v-20.47h23.92v22.31c0 10.12 4.063 15.18 12.19 15.18 7.82 0 11.73-4.677 11.73-14.03v-25.76c0-8.4334-4.293-12.65-12.88-12.65h-11.04v-20.01h9.89c4.293 0 7.667-1.0734 10.12-3.22 2.607-2.1467 3.91-5.29 3.91-9.43v-16.79c0-9.3534-3.757-14.03-11.27-14.03s-11.27 5.06-11.27 15.18v15.87h-23.92v-13.8c0-26.0667 12.19-39.100029 36.57-39.100029z"/></g></svg>',
		'keywords'          => array( 'lnob', 'siffra', 'siffror' ),
	) );

}
add_action( 'acf/init', 'lnob_compat_register_acf_blocks' );

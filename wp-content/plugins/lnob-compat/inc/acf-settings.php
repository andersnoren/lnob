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
	ADD ACF OPTIONS PAGES
	Add options pages to the WordPress admin, available as field group targets in ACF
--------------------------------------------------------------------------------------------------- */

function lnob_compat_add_acf_settings_pages() {
	if ( function_exists( 'acf_add_options_page' ) ) {

		$parent = acf_add_options_page( array(
			'page_title' => __( 'Temainställningar', 'lnob-compat' ),
			'menu_title' => __( 'Temainställningar', 'lnob-compat' ),
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'manage_options',
			'redirect'   => false,
		) );

		acf_add_options_sub_page( array(
			'page_title'  => __( 'Undersida', 'lnob-compat' ),
			'menu_title'  => __( 'Undersida', 'lnob-compat' ),
			'menu_slug'   => 'theme-sub-settings',
			'parent_slug' => $parent['menu_slug'],
		) );

	}
}
// add_action( 'acf/init', 'lnob_compat_add_acf_settings_pages' );

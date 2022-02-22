<?php
/* ---------------------------------------------------------------------------------------------

	Plugin Name: Leave No One Behind kompatibilitetstillägg
	Description: Kompatibilitetstillägg för Leave No One Behind-temat. Det här tillägget måste vara aktivt för att temat ska fungera korrekt.
	Version: 1.0.0
	Author: Anders Norén
	Author URI: https://andersnoren.se

/* --------------------------------------------------------------------------------------------- */


define( 'LNOB_COMPAT_MAIN_FILE', __FILE__);
define( 'LNOB_COMPAT_DIR', untrailingslashit( dirname( __FILE__ )  ) );
define( 'LNOB_COMPAT_URL', untrailingslashit( plugins_url( '',  __FILE__ ) ) );


/*	-----------------------------------------------------------------------------------------------
	SETUP AND GENERAL
	General features and setup actions
--------------------------------------------------------------------------------------------------- */

function lnob_compat_plugins_loaded() {
	load_plugin_textdomain( 'lnob-compat', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'lnob_compat_plugins_loaded' );


/*	-----------------------------------------------------------------------------------------------
	LOAD ADDITIONAL PHP FILES
	Include CPTs, taxonomies and additional functionality
--------------------------------------------------------------------------------------------------- */

require_once( LNOB_COMPAT_DIR . '/inc/acf-settings.php' );
require_once( LNOB_COMPAT_DIR . '/inc/additional-functionality.php' );
require_once( LNOB_COMPAT_DIR . '/inc/register/custom-post-types.php' );
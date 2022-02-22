<?php

/*	-----------------------------------------------------------------------------------------------
	CONDITIONAL DISABLING OF THE BLOCK EDITOR
	Filter for disabling the block editor depending on post type, page template, and so on.
--------------------------------------------------------------------------------------------------- */

function lnob_compat_use_block_editor_for_post( $value, $post ) {

	$post_type 		= get_post_type( $post->ID );
	$post_types 	= array();

	$template_slug 	= get_page_template_slug( $post->ID );
	$page_templates = array();

	if ( in_array( $post_type, $post_types ) ) return false;
	if ( in_array( $template_slug, $page_templates ) ) return false;

	return $value;

}
// add_filter( 'use_block_editor_for_post', 'lnob_compat_use_block_editor_for_post', 10, 2 );
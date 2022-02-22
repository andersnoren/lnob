<?php

/*	-----------------------------------------------------------------------------------------------
	REGISTER POST TYPES
	Register whatever custom post types are needed here.

	https://codex.wordpress.org/Function_Reference/register_post_type
--------------------------------------------------------------------------------------------------- */

function lnob_compat_register_post_types() {

	/* [Singular] ---------------------------- */

	// Labels in Swedish.
	$labels_sv = array(
		'name'						=> _x( '[Plural]', 'post type general name', 'lnob-compat' ),
		'singular_name'				=> _x( '[Singular]', 'post type singular name', 'lnob-compat' ),
		'add_new'					=> _x( 'Lägg till ny', 'post', 'lnob-compat' ),
		'add_new_item'				=> __( 'Lägg till ny [singular]', 'lnob-compat' ),
		'edit_item'					=> __( 'Redigera [singular]', 'lnob-compat' ),
		'new_item'					=> __( 'Ny [singular]', 'lnob-compat' ),
		'view_item'					=> __( 'Visa [singular]', 'lnob-compat' ),
		'view_items'				=> __( 'Visa [plural]', 'lnob-compat' ),
		'search_items'				=> __( 'Sök [plural]', 'lnob-compat' ),
		'not_found'					=> __( 'Inga [plural] hittades.', 'lnob-compat' ),
		'not_found_in_trash'		=> __( 'Inga [plural] hittades i papperskorgen.', 'lnob-compat' ),
		'all_items'					=> __( 'Alla [plural]', 'lnob-compat' ),
		'archives'					=> __( '[Singular]sarkiv', 'lnob-compat' ),
		'attributes'				=> __( '[Singular]sattribut', 'lnob-compat' ),
		'insert_into_item'			=> __( 'Lägg till i [singular]', 'lnob-compat' ),
		'uploaded_to_this_item'		=> __( 'Uppladdade till [singular]en', 'lnob-compat' ),
		'filter_items_list'			=> __( 'Filtrera [plural]-lista', 'lnob-compat' ),
		'items_list_navigation'		=> __( '[Plural]-lista-navigering', 'lnob-compat' ),
		'items_list'				=> __( '[Plural]-lista', 'lnob-compat' ),
		'item_published'			=> __( '[Singular] publicerad.', 'lnob-compat' ),
		'item_published_privately'	=> __( '[Singular] publicerad som privat.', 'lnob-compat' ),
		'item_reverted_to_draft'	=> __( '[Singular] återställd till utkast.', 'lnob-compat' ),
		'item_scheduled'			=> __( '[Singular] schemalagd.', 'lnob-compat' ),
		'item_updated'				=> __( '[Singular] uppdaterad.', 'lnob-compat' ),
		'item_link'					=> _x( '[Singular]slänk', 'navigation link block title', 'lnob-compat' ),
		'item_link_description'		=> _x( 'En länk till en [singular].', 'navigation link block description', 'lnob-compat' ),
		'menu_name'					=> __( '[Plural]', 'lnob-compat' ),
		'all_items' 				=> __( 'Alla [plural]', 'lnob-compat' ),
    	'name_admin_bar' 			=> __( '[Singular]', 'lnob-compat' ),
	);

	// Labels in English.
	$labels_en = array(
		'name'						=> _x( '[Plural]', 'post type general name', 'lnob-compat' ),
		'singular_name'				=> _x( '[Singular]', 'post type singular name', 'lnob-compat' ),
		'add_new'					=> _x( 'Add New', 'post', 'lnob-compat' ),
		'add_new_item'				=> __( 'Add New [Singular]', 'lnob-compat' ),
		'edit_item'					=> __( 'Edit [Singular]', 'lnob-compat' ),
		'new_item'					=> __( 'New [Singular]', 'lnob-compat' ),
		'view_item'					=> __( 'View [Singular]', 'lnob-compat' ),
		'view_items'				=> __( 'View [Plural]', 'lnob-compat' ),
		'search_items'				=> __( 'Search [Plural]', 'lnob-compat' ),
		'not_found'					=> __( 'No [plural] found.', 'lnob-compat' ),
		'not_found_in_trash'		=> __( 'No [plural] found in Trash.', 'lnob-compat' ),
		'all_items'					=> __( 'All [Plural]', 'lnob-compat' ),
		'archives'					=> __( '[Singular] Archives', 'lnob-compat' ),
		'attributes'				=> __( '[Singular] Attributes', 'lnob-compat' ),
		'insert_into_item'			=> __( 'Insert into [singular]', 'lnob-compat' ),
		'uploaded_to_this_item'		=> __( 'Uploaded to this [singular]', 'lnob-compat' ),
		'filter_items_list'			=> __( 'Filter [plural] list', 'lnob-compat' ),
		'items_list_navigation'		=> __( '[Plural] list navigation', 'lnob-compat' ),
		'items_list'				=> __( '[Plural] list', 'lnob-compat' ),
		'item_published'			=> __( '[Singular] published.', 'lnob-compat' ),
		'item_published_privately'	=> __( '[Singular] published privately.', 'lnob-compat' ),
		'item_reverted_to_draft'	=> __( '[Singular] reverted to draft.', 'lnob-compat' ),
		'item_scheduled'			=> __( '[Singular] scheduled.', 'lnob-compat' ),
		'item_updated'				=> __( '[Singular] updated.', 'lnob-compat' ),
		'item_link'					=> _x( '[Singular] Link', 'navigation link block title', 'lnob-compat' ),
		'item_link_description'		=> _x( 'Link to a [singular].', 'navigation link block description', 'lnob-compat' ),
		'menu_name'					=> __( '[Plural]', 'lnob-compat' ),
		'all_items' 				=> __( 'All [Plural]', 'lnob-compat' ),
    	'name_admin_bar' 			=> __( '[Singular]', 'lnob-compat' ),
	);

	$args = array(
		'capability_type'	=> 'page',
		'has_archive'		=> true,
		'labels'			=> $labels_sv,
		'menu_icon'			=> '', // https://developer.wordpress.org/resource/dashicons/
		'menu_position'		=> 20,
		'public'			=> true,
		'rewrite'			=> array(
			'slug'				=> 'SLUG',
		),
		'show_in_rest'		=> true,
		'supports'			=> array( 'title', 'editor', 'thumbnail', 'revisions', 'author', 'page-attributes' ),
	);

	register_post_type( 'lnob_NAME', $args );

}
// add_action( 'init', 'lnob_compat_register_post_types' );

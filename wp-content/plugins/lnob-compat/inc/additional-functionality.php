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

	if ( $post_types && in_array( $post_type, $post_types ) ) return false;
	if ( $page_templates && in_array( $template_slug, $page_templates ) ) return false;

	// Disable the block editor for the page set as the front page.
	if ( get_option( 'page_on_front' ) == $post->ID ) return false;

	return $value;

}
add_filter( 'use_block_editor_for_post', 'lnob_compat_use_block_editor_for_post', 10, 2 );


/*	-----------------------------------------------------------------------------------------------
	REMOVE ADMIN MENU PAGES
	Remove admin menu pages that aren't used on this site.
--------------------------------------------------------------------------------------------------- */

function lnob_compat_remove_admin_menu_pages() {

	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'upload.php' );

}
add_action( 'admin_menu', 'lnob_compat_remove_admin_menu_pages' );


/* ---------------------------------------------------------------------------------------------
   ADD EDIT COLUMNS TO SUBMISSIONS

   @param	$defaults array		The default table headers for this edit view
   --------------------------------------------------------------------------------------------- */

function lnob_compat_menu_order_edit_columns( $defaults ) {

	// Split the table headers after the first item (title)
	$p1 = array_splice( $defaults, 1, 1 );

	// Insert our custom table headers after the title
	$p1['menu_order'] = __( 'Ordning', 'aoe-compat' );

	// Merge them with the rest of the default table headers
	$p2 		= array_splice( $defaults, 0, 1 );
	$defaults 	= array_merge( $p2, $p1, $defaults );

    return $defaults;

}
add_filter( 'manage_lnob_global_goal_posts_columns', 'lnob_compat_menu_order_edit_columns' );


/* ---------------------------------------------------------------------------------------------
   ADD MENU_ORDER TO EDIT COLUMNS IN GLOBAL GOALS
   
   @param	$column_name string		The name of the table column
   @param	$post_id int			The ID of the post
   --------------------------------------------------------------------------------------------- */

function lnob_compat_menu_order_edit_columns_content( $column_name, $post_id ) {

	switch ( $column_name ) {
		case 'menu_order' :
			echo get_post_field( 'menu_order', $post_id ) ?: '0';
			break;
	}

}
add_action( 'manage_lnob_global_goal_posts_custom_column', 'lnob_compat_menu_order_edit_columns_content', 10, 2 );


/* ---------------------------------------------------------------------------------------------
   MAKE THE MENU_ORDER COLUMN SORTABLE
   
   @param	$columns array		The array of columns
   --------------------------------------------------------------------------------------------- */

function lnob_compat_global_goals_menu_order_sortable( $columns ) {

    $columns['menu_order'] = 'menu_order';
 
    return $columns;
}
add_filter( 'manage_edit-lnob_global_goal_sortable_columns', 'lnob_compat_global_goals_menu_order_sortable', 10, 1 );


/* ---------------------------------------------------------------------------------------------
   SORT GLOBAL GOALS BY MENU_ORDER
   
   @param	$query object		The WP_Query object
   --------------------------------------------------------------------------------------------- */

function lnob_compat_lnob_global_goals_menu_order_sorting( $query ) {
	
    if ( is_admin() && $query->is_main_query() && $query->get( 'post_type' ) == 'lnob_global_goal' ) {

		// Handle menu_order sorting, and sort by menu_order by default.
		if ( ( ! isset( $_GET['orderby'] ) ) || $query->get( 'orderby' ) == 'menu_order' ) {
			$query->set( 'orderby', 'menu_order' );

			// Set default order
			if ( ! isset( $_GET['order'] ) ) {
				$query->set( 'order', 'ASC' );
			}
		}

	}

}
add_action( 'pre_get_posts', 'lnob_compat_lnob_global_goals_menu_order_sorting' );


/* ---------------------------------------------------------------------------------------------
   ADD THE GLOBAL GOALS AS POSTS
   --------------------------------------------------------------------------------------------- */

function lnob_bla() {

	$gg = array(
		'1. Ingen fattigdom',
		'2. Ingen hunger',
		'3. God hälsa och välbefinnande',
		'4. God utbildning',
		'5. Jämställdhet',
		'6. Rent vatten och sanitet för alla',
		'7. Hållbar energi för alla',
		'8. Anständiga arbetsvillkor och ekonomisk tillväxt',
		'9. Hållbar industri, innovationer och infrastruktur',
		'10. Minskad ojämlikhet',
		'11. Hållbara städer och samhällen',
		'12. Hållbar konsumtion och produktion',
		'13. Bekämpa klimatförändringarna',
		'14. Hav och marina resurser',
		'15. Ekosystem och biologisk mångfald',
		'16. Fredliga och inkluderande samhällen',
		'17. Genomförande och globalt partnerskap',
	);

	foreach( $gg as $key => $title ) {
		wp_insert_post( array(
			'menu_order'	=> $key + 1,
			'post_status'	=> 'publish',
			'post_title'	=> $title,
			'post_type'		=> 'lnob_global_goal'
		) );
	}

}
// add_action( 'admin_menu', 'lnob_bla' );

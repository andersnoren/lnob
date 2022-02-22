<?php

/*	-----------------------------------------------------------------------------------------------
	REGISTER POST TYPES
	Register whatever custom post types are needed here.

	https://codex.wordpress.org/Function_Reference/register_post_type
--------------------------------------------------------------------------------------------------- */

function lnob_compat_register_post_types() {

	/* Globalt Mål --------------------------- */

	// Labels in Swedish.
	$labels_sv = array(
		'name'						=> _x( 'Globala mål', 'post type general name', 'lnob-compat' ),
		'singular_name'				=> _x( 'Globalt mål', 'post type singular name', 'lnob-compat' ),
		'add_new'					=> _x( 'Lägg till nytt', 'post', 'lnob-compat' ),
		'add_new_item'				=> __( 'Lägg till nytt globalt mål', 'lnob-compat' ),
		'edit_item'					=> __( 'Redigera globalt mål', 'lnob-compat' ),
		'new_item'					=> __( 'Nytt globalt mål', 'lnob-compat' ),
		'view_item'					=> __( 'Visa globalt mål', 'lnob-compat' ),
		'view_items'				=> __( 'Visa globala mål', 'lnob-compat' ),
		'search_items'				=> __( 'Sök globala mål', 'lnob-compat' ),
		'not_found'					=> __( 'Inga globala mål hittades.', 'lnob-compat' ),
		'not_found_in_trash'		=> __( 'Inga globala mål hittades i papperskorgen.', 'lnob-compat' ),
		'all_items'					=> __( 'Alla globala mål', 'lnob-compat' ),
		'archives'					=> __( 'Globala mål-arkiv', 'lnob-compat' ),
		'attributes'				=> __( 'Globalt mål-attribut', 'lnob-compat' ),
		'insert_into_item'			=> __( 'Lägg till i globalt mål', 'lnob-compat' ),
		'uploaded_to_this_item'		=> __( 'Uppladdade till globalt mål', 'lnob-compat' ),
		'filter_items_list'			=> __( 'Filtrera globala mål-lista', 'lnob-compat' ),
		'items_list_navigation'		=> __( 'Globala mål-lista-navigering', 'lnob-compat' ),
		'items_list'				=> __( 'Globala mål-lista', 'lnob-compat' ),
		'item_published'			=> __( 'Globalt mål publicerat.', 'lnob-compat' ),
		'item_published_privately'	=> __( 'Globalt mål publicerat som privat.', 'lnob-compat' ),
		'item_reverted_to_draft'	=> __( 'Globalt mål återställt till utkast.', 'lnob-compat' ),
		'item_scheduled'			=> __( 'Globalt mål schemalagt.', 'lnob-compat' ),
		'item_updated'				=> __( 'Globalt mål uppdaterat.', 'lnob-compat' ),
		'item_link'					=> _x( 'Globalt mål-länk', 'navigation link block title', 'lnob-compat' ),
		'item_link_description'		=> _x( 'En länk till ett globalt mål.', 'navigation link block description', 'lnob-compat' ),
		'menu_name'					=> __( 'Globala målen', 'lnob-compat' ),
		'all_items' 				=> __( 'Alla globala mål', 'lnob-compat' ),
    	'name_admin_bar' 			=> __( 'Globalt mål', 'lnob-compat' ),
	);

	$args = array(
		'capability_type'	=> 'page',
		'has_archive'		=> true,
		'labels'			=> $labels_sv,
		'menu_icon'			=> 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAxIiBoZWlnaHQ9IjMwMSIgdmlld0JveD0iMCAwIDMwMSAzMDEiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTU2LjAyNyA2NS42NzEzQzE2My4yOTUgNjYuMjY4MyAxNzAuMjY1IDY3LjY2MTMgMTc2LjkzNiA2OS44NTA0TDIwMC41MzQgOC43NTYwMkMxODYuNTk0IDMuNzgwOTEgMTcxLjY1OSAwLjc5NTg0MyAxNTYuMDI3IDAuMjk4MzMyVjY1LjY3MTNaIiBmaWxsPSJibGFjayIvPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTE4NS43OTggNzMuMjMzOEMxOTIuNDY5IDc2LjIxODkgMTk4Ljc0MiA4MC4xOTkgMjA0LjMxOCA4NC44NzU2TDI0OC41MjYgMzYuNjE3QzIzNi44NzcgMjYuNjY2OCAyMjMuNjM0IDE4LjMwODYgMjA5LjI5NiAxMi4xMzk1TDE4NS43OTggNzMuMjMzOFoiIGZpbGw9ImJsYWNrIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMjI0LjIzMSAxMDguNDU4TDI4Mi45NzcgNzkuMzAzNUMyNzUuNjA5IDY1Ljg3MDcgMjY2LjM0OSA1My42MzE5IDI1NS40OTYgNDIuOTg1MkwyMTEuMDg4IDkxLjI0MzhDMjE2LjI2NiA5Ni40MTc5IDIyMC42NDcgMTAyLjE4OSAyMjQuMjMxIDEwOC41NTciIGZpbGw9ImJsYWNrIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMjg3LjA1OSA4Ny43NjEyTDIyOC4zMTQgMTE3LjAxNUMyMzEuMzAxIDEyMy41ODIgMjMzLjI5MiAxMzAuNDQ4IDIzNC4yODggMTM3LjcxMUwyOTkuNTA1IDEzMS41NDJDMjk3LjUxNCAxMTYuMTE5IDI5My4zMzIgMTAxLjM5MyAyODcuMDU5IDg3Ljc2MTJaIiBmaWxsPSJibGFjayIvPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTIzNS4yODMgMTUwLjI0OUMyMzUuMjgzIDE1Ni4yMTkgMjM0LjU4NyAxNjIuMzg4IDIzMy4yOTIgMTY4LjE1OUwyOTYuMzE5IDE4Ni4yNjlDMjk5LjMwNiAxNzQuNzI2IDMwMC43IDE2Mi41ODcgMzAwLjcgMTUwLjE0OUMzMDAuNyAxNDYuOTY1IDMwMC43IDE0My44ODEgMzAwLjQwMSAxNDAuODk2TDIzNS4xODQgMTQ3LjA2NVYxNTAuMDUiIGZpbGw9ImJsYWNrIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMjMwLjgwMyAxNzcuMzEzQzIyOC41MTMgMTgzLjk4IDIyNS40MjYgMTkwLjI0OCAyMjEuNjQzIDE5Ni4yMTlMMjczLjkxNiAyMzUuNTIyQzI4Mi41NzkgMjIzLjM4MyAyODkuMjUgMjA5Ljg1IDI5My44MyAxOTUuMzIzTDIzMC45MDIgMTc3LjQxMyIgZmlsbD0iYmxhY2siLz4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0yMTYuMDY3IDIwMy43OEMyMTEuMzg3IDIwOS40NTIgMjA2LjExIDIxNC40MjcgMjAwLjEzNiAyMTguNzA2TDIzNC41ODYgMjc0LjQyN0MyNDcuMzMxIDI2NS43NyAyNTguNzgyIDI1NS4yMjMgMjY4LjQ0IDI0My4xODNMMjE2LjA2NyAyMDMuNzhaIiBmaWxsPSJibGFjayIvPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTIyNi41MjEgMjc5LjYwMkwxOTIuMTcgMjIzLjg4MUMxODUuODk3IDIyNy4zNjQgMTc5LjIyNiAyMzAuMTUgMTcyLjA1NyAyMzEuODQxTDE4NC4yMDUgMjk2LjUxOEMxOTkuMzM5IDI5Mi45MzYgMjEzLjU3OCAyODcuMTY0IDIyNi41MjEgMjc5LjYwMloiIGZpbGw9ImJsYWNrIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTYyLjc5OCAyMzMuODMxQzE1OC44MTUgMjM0LjQyOCAxNTQuODMyIDIzNC44MjYgMTUwLjY1IDIzNC44MjZDMTQ3LjI2NSAyMzQuODI2IDE0My45NzkgMjM0LjUyOCAxNDAuNjkzIDIzNC4xM0wxMjguNzQ1IDI5OC41MDhDMTM1LjcxNSAyOTkuNTAzIDE0My4xODMgMzAwLjE5OSAxNTAuNjUgMzAwLjE5OUMxNTguOTE0IDMwMC4xOTkgMTY2Ljk3OSAyOTkuNTAzIDE3NC45NDUgMjk4LjIwOUwxNjIuNzk4IDIzMy44MzFaIiBmaWxsPSJibGFjayIvPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTEzMS40MzMgMjMyLjUzN0MxMjQuMDY1IDIzMC44NDUgMTE3LjA5NiAyMjguMTU5IDExMC41MjQgMjI0LjU3N0w3Ni4xNzI5IDI4MC4yOThDODkuNDE1NiAyODcuOTYgMTA0LjA1MiAyOTMuNjMxIDExOS4yODYgMjk2LjkxNUwxMzEuNDMzIDIzMi41MzdaIiBmaWxsPSJibGFjayIvPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTg2LjIyOTQgMjA0Ljk3NkwzNC4wNTU1IDI0NC41NzdDNDMuODEzMiAyNTYuNTE4IDU1LjI2MzYgMjY2Ljk2NSA2OC4xMDc5IDI3NS40MjNMMTAyLjU1OSAyMTkuNzAyQzk2LjU4NDUgMjE1LjcyMiA5MS4wMDg3IDIxMC43NDcgODYuMjI5NCAyMDQuOTc2WiIgZmlsbD0iYmxhY2siLz4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik04MC40NTQ0IDE5Ny41MTNDNzYuNDcxNyAxOTEuMzQ0IDcyLjk4NjggMTg0LjU3OCA3MC40OTc2IDE3Ny42MTJMNy41NzAyNiAxOTUuNTIzQzEyLjM0OTYgMjEwLjQ0OCAxOS41MTg1IDIyNC41NzggMjguNDc5NyAyMzcuMTE1TDgwLjQ1NDQgMTk3LjUxM1oiIGZpbGw9ImJsYWNrIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNNjYuMDE3IDE1MC4yNDlWMTQ2LjI2OUwwLjk5ODcxOCAxNDAuMjk5QzAuNzk5NTgxIDE0My41ODMgMC43MDAwMTIgMTQ2Ljg2NiAwLjcwMDAxMiAxNTAuMjQ5QzAuNzAwMDEyIDE2Mi42ODcgMi4yOTMxMSAxNzQuODI2IDUuMTgwNiAxODYuNDY4TDY4LjIwNzUgMTY4LjM1OUM2Ni45MTMxIDE2Mi4zODkgNjYuMjE2MSAxNTYuNDE4IDY2LjIxNjEgMTUwLjE1IiBmaWxsPSJibGFjayIvPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTY3LjAxMjYgMTM2LjgxN0M2OC4yMDc1IDEyOS40NTMgNzAuMjk4NCAxMjIuMzg5IDczLjI4NTUgMTE1LjkyMUwxNC41NCA4Ni41Njc5QzguMjY3MjEgMTAwLjQ5OCAzLjk4NTc2IDExNS40MjQgMS45OTQzOSAxMzEuMDQ1TDY3LjIxMTggMTM3LjAxNiIgZmlsbD0iYmxhY2siLz4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik03Ny42NjY1IDEwNy40NjNDODEuMzUwNSAxMDEuMDk1IDg1LjkzMDcgOTUuMjI0NiA5MS4xMDgyIDkwLjE1TDQ2Ljk5OTQgNDEuNzkxOUMzNS44NDc3IDUyLjMzOTIgMjYuMzg4NyA2NC42Nzc0IDE4LjkyMSA3OC4yMDk3TDc3LjY2NjUgMTA3LjQ2M1oiIGZpbGw9ImJsYWNrIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNOTguMTc3NiA4My44ODAyQzEwMy43NTMgNzkuNTAyMSAxMDkuODI3IDc1LjkyIDExNi4yOTkgNzIuOTM1TDkyLjYwMTcgMTEuNzQxMUM3OC42NjIxIDE3LjUxMjIgNjUuNTE5MSAyNS42NzE0IDU0LjA2ODcgMzUuMjIzNkw5OC4xNzc2IDgzLjY4MTIiIGZpbGw9ImJsYWNrIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTI1LjE2MSA2OS42NTE4QzEzMS45MzEgNjcuMzYzMiAxMzkuMSA2Ni4wNjk3IDE0Ni41NjggNjUuNjcxN1YwLjE5OTIxOUMxMzAuODM2IDAuNjk2NzMgMTE1LjcwMiAzLjQ4Mjc5IDEwMS40NjMgOC40NTc5TDEyNS4wNjEgNjkuNjUxOCIgZmlsbD0iYmxhY2siLz4KPC9zdmc+Cg==',
		'menu_position'		=> 20,
		'public'			=> true,
		'show_in_rest'		=> false,
		'supports'			=> array( 'title', 'editor', 'revisions', 'author', 'page-attributes' ),
	);

	register_post_type( 'lnob_global_goal', $args );

}
add_action( 'init', 'lnob_compat_register_post_types' );

<?php

$front_page_id = get_option( 'page_on_front' );

?>

<nav id="menu" class="menu cover-modal bg-white focus-loop">

	<div class="menu-inner section-inner">

		<div class="menu-top d-flex justify-between align-center">

			<div class="logos pv-24 pv-t-32">

				<a class="logo lnob-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php lnob_the_svg( 'logos/lnob-logo' ); ?>
					<span class="screen-reader-text"><?php _e( 'Globala målen', 'lnob' ); ?></span>
				</a>

				<a class="logo forumciv-logo" href="https://www.forumciv.org/sv">
					<?php lnob_the_svg( 'logos/forumciv-logo' ); ?>
					<span class="screen-reader-text"><?php _e( 'ForumCiv', 'lnob' ); ?></span>
				</a>

			</div><!-- .logos -->

			<button class="nav-untoggle reset" data-toggle-target="#menu" data-set-focus=".nav-toggle" data-toggle-body-class="showing-navigation" data-toggle-scroll-lock="true">
				<div class="icon-text">
					<div class="text h-uppercase fs-14 d-none d-t-block"><?php _e( 'Stäng', 'lnob' ); ?></div>
					<div class="icon fill-cc"><?php lnob_the_svg( 'cross' ); ?></div>
				</div><!-- .icon-text -->
			</button><!-- .nav-toggle -->

		</div><!-- .menu-top -->

		<div class="menu-grid-wrapper pv-tl-48">
			<div class="menu-grid grid cols-tl-6 no-v-gutter">

				<div class="col col-main-menu">

					<?php

					$main_menu 				= get_field( 'menu_main_menu', $front_page_id );
					$introduction_text 		= ! empty( $main_menu['introduction_text'] ) ? $main_menu['introduction_text'] : __( 'Introduktion', 'lnob' );
					$missing_in_stats_text 	= ! empty( $main_menu['missing_in_stats_text'] ) ? $main_menu['missing_in_stats_text'] : __( 'Vem syns inte?', 'lnob' );
					$recommendations_text 	= ! empty( $main_menu['recommendations_text'] ) ? $main_menu['recommendations_text'] : __( 'Rekommendationer', 'lnob' );

					$main_menu_arr = array(
						'introduction' => array(
							'title'	=> $introduction_text,
							'url'	=> '#section-introduction'
						),
						'missing-in-stats' => array(
							'title'	=> $missing_in_stats_text,
							'url'	=> '#section-missing-in-stats'
						),
						'recommendations' => array(
							'title'	=> $recommendations_text,
							'url'	=> '#section-recommendations'
						),
					);

					?>

					<div class="main-menu-wrapper pv-32 pv-t-48 pv-tl-0">

						<ul class="main-menu reset-list-style">

							<?php foreach ( $main_menu_arr as $slug => $menu_item ) : ?>
								<li class="menu-item menu-item-<?php echo $slug; ?>">
									<?php
									echo lnob_get_squircle_link( array(
										'icon'	=> array(
											'name'	=> 'arrow-right',
										),
										'text'	=> $menu_item['title'],
										'url'	=> $menu_item['url'],
									) );
									?>
								</li>
							<?php endforeach; ?>

						</ul>

					</div>
					
				</div>

				<div class="col col-menu-secondary">

					<div class="menu-secondary pv-32 pv-t-48 pv-tl-0">

						<?php

						$secondary_menu 		= get_field( 'menu_secondary_menu', $front_page_id );
						$text 					= ! empty( $secondary_menu['text'] ) ? $secondary_menu['text'] : '';
						$external_links 		= ! empty( $secondary_menu['external_links'] ) ? $secondary_menu['external_links'] : array();
						$external_links_markup	= $external_links ? lnob_get_external_links_list_markup( array( 
							'links_data' => $external_links,
							'size'
						) ) : '';

						// Get the most recently modified page/goal on the site, and use that to display the updated date.
						$last_updated_posts = get_posts( array(
							'post_type'			=> array( 'page', 'lnob_global_goal' ),
							'posts_per_page'	=> 1,
							'orderby'     		=> 'modified',
     						'order'       		=> 'DESC',
						) );

						$updated_date_str = $last_updated_posts ? sprintf( __( 'Uppdaterad %s', 'lnob' ), get_the_modified_date( 'j F Y', $last_updated_posts[0] ) ) : '';

						if ( $text ) : 
							?>
							<div class="menu-secondary-text fs-16 contain-margins">
								<?php echo wpautop( $text ); ?>
							</div>
							<?php
						endif;

						if ( $updated_date_str ) : 
							?>

							<div class="modified-date fs-14 pu-16">
								<?php echo $updated_date_str; ?>
							</div><!-- .modified-date -->

							<?php
						endif;

						if ( $external_links_markup ) : 
							?>
							<div class="external-links-wrapper pu-24 pu-t-32 fs-14 fs-t-16">
								<?php echo $external_links_markup; ?>
							</div>
							<?php
						endif;
						?>

					</div><!-- .menu-secondary -->

				</div>

			</div><!-- .menu-grid -->

		</div><!-- .menu-grid-wrapper -->

		<div class="menu-gg pu-32 pu-t-48">

			<?php

			$GLOBALS['global_goals'] = get_posts( array(
				'order'				=> 'ASC',
				'orderby'			=> 'menu_order',
				'post_type'			=> 'lnob_global_goal',
				'posts_per_page'	=> -1,
			) );

			$global_goals_menu 	= get_field( 'menu_global_goals_menu', $front_page_id );
			$title 				= ! empty( $global_goals_menu['title'] ) ? $global_goals_menu['title'] : '';
			$text 				= ! empty( $global_goals_menu['text'] ) ? $global_goals_menu['text'] : '';

			?>

			<div class="menu-gg-header pd-24 pd-t-32">

				<div class="menu-gg-header-grid grid cols-t-6 align-baseline">

					<?php if ( $title ) : ?>
						<div class="col">
							<h2 class="no-margin h-uppercase fs-14 fs-t-16"><?php echo $title; ?></h2>
						</div>
					<?php endif; ?>

					<?php if ( $text ) : ?>
						<div class="col contain-margins fs-14 fs-t-16"><?php echo wpautop( $text ); ?></div>
					<?php endif; ?>

				</div><!-- .menu-gg-header-grid -->

			</div><!-- .menu-gg-header -->

			<?php if ( $GLOBALS['global_goals'] ) : ?>

				<div class="menu-gg-grid grid cols-6 cols-t-4 cols-tl-3 cols-d-2">

					<?php
					foreach ( $GLOBALS['global_goals'] as $global_goal ) : 
						$gg_number 	= get_field( 'general_global_goal', $global_goal ) ?: 1;
						$gg_icon 	= 'gg/gg-' . $gg_number;
						?>

						<div class="col gg-menu-item">
							<a class="gg-menu-item-link" href="<?php echo esc_url( get_permalink( $global_goal ) ); ?>" data-scroll-to="#gg-<?php echo esc_attr( get_post_field( 'post_name', $global_goal->ID ) ); ?>">
								<?php lnob_the_svg( $gg_icon ); ?>
								<span class="screen-reader-text"><?php echo get_the_title( $global_goal ); ?></span>
							</a>
						</div>

						<?php 
					endforeach;
					?>

				</div><!-- .menu-gg-grid -->

			<?php endif; ?>

		</div><!-- .menu-gg -->

		<div class="menu-bottom">

			<button class="nav-untoggle reset" data-toggle-target="#menu" data-set-focus=".nav-toggle" data-toggle-body-class="showing-navigation" data-toggle-scroll-lock="true">
				<div class="icon-text">
					<div class="icon fill-cc"><?php lnob_the_svg( 'cross', 16, 16 ); ?></div>
					<div class="text h-uppercase fs-14"><?php _e( 'Stäng', 'lnob' ); ?></div>
				</div><!-- .icon-text -->
			</button><!-- .nav-toggle -->

		</div><!-- .menu-top -->

	</div><!-- .menu-inner -->

</nav><!-- #menu -->
	<?php

	$post_id = get_option( 'page_on_front' );

	$footer_text 			= get_field( 'footer_text', $post_id );
	$footer_links 			= get_field( 'footer_links', $post_id );
	$footer_links_markup	= $footer_links ? lnob_get_external_links_list_markup( array( 'links_data' => $footer_links ) ) : '';
	$footer_copyright_text 	= get_field( 'footer_copyright_text', $post_id ) ?: get_bloginfo( 'name' );
	$footer_menu 			= get_field( 'footer_footer_menu', $post_id );

	?>

	<footer id="site-footer" class="bg-black c-white pu-48 pd-32 pv-t-64 pos-sticky">

		<div class="footer-inner section-inner">

			<div class="footer-top">

				<div class="footer-top-grid grid cols-tl-6">

					<div class="col d-flex">
						<a class="footer-logo" href="https://www.forumciv.org/sv">
							<?php lnob_the_svg( 'logos/forumciv-logo-inv' ); ?>
						</a>
					</div>

					<div class="col">

						<?php if ( $footer_text ) : ?>

							<div class="footer-text contain-margins fs-intro-text mw-thin">
								<?php echo wpautop( $footer_text ); ?>
							</div><!-- .footer-text -->

						<?php endif; ?>

						<?php if ( $footer_links_markup ) : 
							$footer_links_classes = $footer_text ? ' pu-24 pu-t-32' : '';
							?>

							<div class="footer-links-wrapper fs-14 fs-t-16 c-yellow<?php echo $footer_links_classes; ?>">
								<?php echo $footer_links_markup; ?>
							</div><!-- .footer-links-wrapper -->

						<?php endif; ?>

					</div><!-- .grid -->

				</div><!-- .footer-top-grid -->

			</div><!-- .footer-top -->

			<div class="footer-bottom pu-64 pu-t-96 pu-d-128 d-flex justify-between align-baseline fs-12 fs-t-16 fw-600">

				<p class="footer-copyright no-margin">
					<?php printf( _x( '&copy; %1$d %2$s', 'Translators: %1$d = Current year, %2$s = Site name', 'lnob' ), esc_html( date_i18n( 'Y' ) ), '<a href="' . esc_url( home_url() ) . '" rel="home">' . $footer_copyright_text . '</a>' );  ?>
				</p>

				<?php if ( $footer_menu ) : ?>

					<div class="footer-menu-container">

						<ul class="footer-menu horizontal-list reset-list-style">

							<?php 
							foreach ( $footer_menu as $menu_item ) : 
								$url 			= ! empty( $menu_item['link']['url'] ) ? $menu_item['link']['url'] : '';
								$title 			= ! empty( $menu_item['link']['title'] ) ? $menu_item['link']['title'] : '';
								$target_attr 	= ! empty( $menu_item['link']['target'] ) ? ' target="' . esc_attr( $menu_item['link']['title'] ) . '"' : '';
								?>

								<li><a href="<?php echo esc_url( $url ); ?>"<?php echo $target_attr; ?>><?php echo $title; ?></a></li>

								<?php 
							endforeach;
							?>

						</ul><!-- .footer-menu -->

					</div><!-- .footer-menu-container -->

				<?php endif; ?>

			</div><!-- .footer-bottom -->

		</div><!-- .footer-inner -->

	</footer><!-- #site-footer -->
		
		<?php wp_footer(); ?>

    </body>
</html>
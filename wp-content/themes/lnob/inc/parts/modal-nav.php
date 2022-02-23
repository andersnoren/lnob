<nav id="menu" class="menu bg-black pv-40 pv-t-80 d-none">

	<div class="section-inner">

		<?php if ( has_nav_menu( 'mobile' ) ) : ?>

			<ul class="mobile-menu reset-list-style">

				<?php 
				wp_nav_menu( array( 
					'container'         => '',
					'items_wrap'        => '%3$s',
					'theme_location'    => 'mobile' 
				) ); 
				?>

			</ul><!-- .mobile-menu -->

		<?php endif; ?>

	</div><!-- .section-inner -->

</nav><!-- #menu -->
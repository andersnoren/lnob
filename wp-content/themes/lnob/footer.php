        <footer id="site-footer" class="pv-20 pv-t-60 pv-d-80">

			<div class="footer-inner section-inner">

            	<?php 
				printf( _x( '&copy; %1$d %2$s', 'Translators: %1$d = Current year, %2$s = Site name', 'lnob' ), esc_html( date_i18n( 'Y' ) ), '<a href="' . esc_url( home_url() ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a>' ); 
				?>

			</div><!-- .footer-inner -->

        </footer><!-- #site-footer -->

        <?php wp_footer(); ?>

    </body>
</html>
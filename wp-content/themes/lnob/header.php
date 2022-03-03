<!DOCTYPE html>

<html <?php language_attributes(); ?> class="no-js">

	<head>
		
		<meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ); ?>" charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="author" content="<?php bloginfo( 'name' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        
        <link rel="profile" href="http://gmpg.org/xfn/11">
		 
		<?php wp_head(); ?>
	
	</head>
    
    <body <?php body_class(); ?>>

		<?php wp_body_open(); ?>
    
        <header id="site-header">

			<div class="logos section-inner pv-24 pv-t-32">

				<a class="logo lnob-logo" href="https://www.globalamalen.se">
					<?php lnob_the_svg( 'logos/lnob-logo' ); ?>
					<span class="screen-reader-text"><?php _e( 'Globala målen', 'lnob' ); ?></span>
				</a>

				<a class="logo forumciv-logo" href="https://www.forumciv.org/sv">
					<?php lnob_the_svg( 'logos/forumciv-logo' ); ?>
					<span class="screen-reader-text"><?php _e( 'ForumCiv', 'lnob' ); ?></span>
				</a>

			</div><!-- .logos -->
            
		</header><!-- #site-header -->

		<div class="navigation-bar pu-24 pu-t-40 pu-tl-56 d-no-js-none">
			
			<a class="scroll-to-top nav-bar-button" href="#site-header">
				<div class="squircle"><?php lnob_the_svg( 'squircles/squircle' ); ?></div>
				<div class="icon stroke-cc"><?php lnob_the_svg( 'arrow-up', 18, 18 ); ?></div>
				<div class="screen-reader-text"><?php _e( 'Tillbaka till toppen', 'lnob' ); ?></div>
			</a><!-- .scroll-to-top -->

			<button class="nav-toggle nav-bar-button reset" data-toggle-target="#menu" data-set-focus=".nav-untoggle:first" data-toggle-body-class="showing-navigation" data-toggle-scroll-lock="true" data-untoggle-on-escape>
				<div class="icon-text d-none d-t-flex">
					<div class="text h-uppercase fs-14"><?php _e( 'Meny', 'lnob' ); ?></div>
					<div class="icon stroke-cc"><?php lnob_the_svg( 'menu', 20, 14 ); ?></div>
				</div><!-- .icon-text -->
				<div class="icon-only d-t-none">
					<div class="squircle"><?php lnob_the_svg( 'squircles/squircle' ); ?></div>
					<div class="icon stroke-cc"><?php lnob_the_svg( 'menu', 20, 14 ); ?></div>
				</div><!-- .icon-only -->
			</button><!-- .nav-toggle -->

		</div><!-- .navigation-bar -->
		
		<?php include( locate_template( 'inc/parts/modal-nav.php' ) ); ?>
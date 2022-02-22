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
    
        <header id="site-header" class="pv-20 pv-t-60 pv-d-80">

			<div class="header-inner section-inner">

				<?php $title_wrapper = ( is_front_page() && is_home() ) ? 'h1' : 'div'; ?>
            
				<<?php echo $title_wrapper; ?> class="site-title h1 lh-s no-margin">
					<a class="color-i td-n" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				</<?php echo $title_wrapper; ?>><!-- .site-title -->

				<button class="toggle nav-toggle reset d-tl-none" data-toggle-target=".mobile-menu-wrapper" data-toggle-type="slidetoggle">
					<span class="screen-reader-text"><?php _e( 'Meny', 'darwin' ); ?></span>
					<div class="bars">
						<div class="bar"></div>
						<div class="bar"></div>
						<div class="bar"></div>
					</div><!-- .bars -->
				</button><!-- .nav-toggle -->

				<?php if ( has_nav_menu( 'main' ) ) : ?>

					<nav class="main-menu-wrapper d-none d-tl-block" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" role="navigation">
						<ul class="main-menu reset-list-style">
							<?php 
							wp_nav_menu( array( 
								'container'         => '',
								'items_wrap'        => '%3$s',
								'theme_location'    => 'main' 
							) ); 
							?>
						</ul><!-- .main-menu -->
					</nav>

				<?php endif; ?>
			
			</div><!-- .section-inner -->
            
		</header><!-- #site-header -->
		
		<?php include( locate_template( 'inc/parts/modal-nav.php' ) ); ?>
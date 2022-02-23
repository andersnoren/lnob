<article id="post-<?php the_ID(); ?>" <?php post_class( 'preview' ); ?>>

	<figure class="preview-image bg-image ar" style="background-image: url( <?php lnob_the_post_thumbnail_url(); ?> );">
		<img class="d-none" src="<?php lnob_the_post_thumbnail_url(); ?>" loading="lazy" />
	</figure>

	<?php the_title( '<h2 class="preview-title no-margin"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="c-i td-n">', '</a></h2>' ); ?>

</article>
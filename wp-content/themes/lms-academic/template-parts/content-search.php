<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lms_academic
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				lms_academic_posted_on();
				lms_academic_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php lms_academic_post_thumbnail(); ?>

	<div class="entry-content">
            <?php
                    echo wp_kses_post(lms_academic_get_excerpt($post->ID, 450));

            ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<a href="<?php echo esc_url(get_the_permalink()); ?>" class="btn btn-default"><?php esc_html_e("Read More", "lms-academic") ?></a>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->

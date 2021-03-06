<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Andromeda
 */
?>

	</div><!-- #content -->

	<?php get_sidebar( 'footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'andromeda' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'andromeda' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'andromeda' ), 'Andromeda', '<a href="https://themes.redradar.net" rel="designer">Kelly Dwan & Mel Choyce</a>' ); ?>
		</div><!-- .site-info -->

		<?php if ( $copyright = get_theme_mod( 'copyright', false ) ) : ?>
		<div class="site-copyright">
			<?php echo esc_html( $copyright ); ?>
		</div><!-- .site-copyright -->
		<?php endif; ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

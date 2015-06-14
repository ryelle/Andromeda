<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Andromeda
 */
?>

<section class="no-results not-found">

	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		<header class="page-header">
			<h2 class="page-title"><?php _e( 'Nothing Found', 'andromeda' ); ?></h2>
		</header><!-- .page-header -->

		<div class="page-content">
			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'andromeda' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
		</div><!-- .page-content -->

	<?php elseif ( is_search() ) : ?>

		<div class="page-content">
			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'andromeda' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .page-content -->

	<?php else : ?>

		<div class="page-content">
			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'andromeda' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .page-content -->

	<?php endif; ?>

</section><!-- .no-results -->

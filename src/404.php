<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Andromeda
 */

get_header(); ?>

	<header class="archive-header">
		<h1 class="archive-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'andromeda' ); ?></h1>
	</header><!-- .archive-header -->

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'andromeda' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

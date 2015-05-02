<?php
/**
 * The template partial for archive headers
 *
 * @package Andromeda
 */
?>
	<header class="archive-header">
		<?php
			the_archive_title( '<h1 class="archive-title">', '</h1>' );
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
		?>
	</header><!-- .page-header -->
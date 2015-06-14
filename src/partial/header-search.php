<?php
/**
 * The template partial for page & post headers
 *
 * @package Andromeda
 */
?>
	<header class="search-header">
		<h1 class="search-title"><?php printf( __( 'Search Results for: %s', 'andromeda' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
	</header><!-- .page-header -->

<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Andromeda
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area sidebar-widgets" role="complementary">
	<h1 class="screen-reader-text"><?php _e( 'Sidebar', 'andromeda' ); ?></h1>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->

<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Andromeda
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) {
	return;
}
?>

<div id="tertiary" class="widget-area footer-widgets" role="complementary">
	<h1 class="screen-reader-text"><?php _e( 'Footer Content', 'andromeda' ); ?></h1>
	<?php dynamic_sidebar( 'sidebar-2' ); ?>
</div><!-- #secondary -->

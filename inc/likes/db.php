<?php
function theme_create_or_update_likes_table(): void {
	global $wpdb;
	$wpdb->show_errors();

	$table_name      = $wpdb->prefix . 'likes';
	$charset_collate = $wpdb->get_charset_collate();

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	// dbDelta($sql); // uncomment later
}

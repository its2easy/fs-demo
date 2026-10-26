<?php
function theme_create_or_update_likes_table(): void {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name     = Theme_Likes_Repository::table_name();

	$sql = "CREATE TABLE {$table_name} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		post_id bigint(20) unsigned NOT NULL,
		user_ip varchar(45) NOT NULL,
		action_type enum('like','dislike') NOT NULL,
		page_url varchar(255) NOT NULL DEFAULT '',
		created_at datetime NOT NULL,
		PRIMARY KEY (id),
		UNIQUE KEY post_ip (post_id, user_ip)
	)
    {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta($sql);
}

function theme_drop_likes_table(): void {
	global $wpdb;

	$table_name = Theme_Likes_Repository::table_name();
	$wpdb->query("DROP TABLE IF EXISTS {$table_name}");
}

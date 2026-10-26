<?php
// Entrypoint for likes module
require_once 'db.php';
require_once 'theme-likes-list-table.php';
require_once 'dashboard.php';

add_action('after_switch_theme', 'theme_init_likes_module');
function theme_init_likes_module() {
	theme_create_or_update_likes_table();
}

add_action('wp_ajax_theme_post_likes', 'theme_post_likes_handler');
add_action('wp_ajax_nopriv_theme_post_likes', 'theme_post_likes_handler');
function theme_post_likes_handler() {
	$post_id     = absint($_POST['post_id'] ?? 0);
	$action_type = sanitize_key($_POST['action_type'] ?? '');
	$user_ip     = $_SERVER['REMOTE_ADDR'] ?? '';
	$created_at  = current_time('mysql');

	echo random_int(-20, 40);
	wp_die();
}

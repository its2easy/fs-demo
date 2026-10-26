<?php
// Entrypoint for likes module
require_once 'db.php';
require_once 'Theme_Like_Action.php';
require_once 'Theme_Likes_Repository.php';
require_once 'Theme_Likes_List_Table.php'; // dashboard table view
require_once 'dashboard.php';

add_action('after_switch_theme', 'theme_init_likes_module');
function theme_init_likes_module() {
	theme_create_or_update_likes_table();
}
// add_action('switch_theme', 'theme_drop_likes_table'); // optionally, better to use a custom button/action


// frontend ajax request handler
add_action('wp_ajax_theme_post_likes', 'theme_post_likes_handler');
add_action('wp_ajax_nopriv_theme_post_likes', 'theme_post_likes_handler');
function theme_post_likes_handler() {
	$post_id     = absint($_POST['post_id'] ?? 0);
	$action_type = Theme_Like_Action::tryFrom(sanitize_key($_POST['action_type'] ?? ''));
	// ip limitation: a user can be behind a shared NAT/proxy or change its ip to vote many times
	$user_ip     = filter_var($_SERVER['REMOTE_ADDR'] ?? '', FILTER_VALIDATE_IP);

	if (!$post_id || !$user_ip || $action_type === null || get_post_status($post_id) !== 'publish') {
		wp_die('Invalid request', '', ['response' => 400]);
	}

	// nonce skipped for simplicity and because the vote is public anyway

	$previous_vote = Theme_Likes_Repository::get_vote($post_id, $user_ip);
	if ($previous_vote === $action_type) { // click like, then again click like
		$saved = Theme_Likes_Repository::remove_vote($post_id, $user_ip);
	} else {
		$saved = Theme_Likes_Repository::set_vote($post_id, $user_ip, $action_type, theme_likes_page_url());
	}

	if (!$saved) { // sql error, see $wpdb->last_error
		wp_die('Could not save the vote', '', ['response' => 500]);
	}

	echo Theme_Likes_Repository::get_sum($post_id); // return fresh value
	wp_die();
}

// get relative page url where a user has voted
function theme_likes_page_url(): string {
	$referer = wp_get_referer();
	if (!$referer)  return '';

	$path = wp_parse_url($referer, PHP_URL_PATH);
	if (!$path) return '';

	$query = wp_parse_url($referer, PHP_URL_QUERY);
	return $query ? $path . '?' . $query : $path;
}

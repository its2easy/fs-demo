<?php

require 'db.php';
require 'dashboard.php';

add_action('after_switch_theme', 'theme_init_likes_module');
function theme_init_likes_module() {
	theme_create_or_update_likes_table();
}

add_action('wp_ajax_theme_post_likes', 'theme_post_likes_handler');
add_action('wp_ajax_nopriv_theme_post_likes', 'theme_post_likes_handler');
function theme_post_likes_handler() {
	echo random_int(-20, 40);
	wp_die();
}

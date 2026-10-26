<?php
add_action('admin_menu', 'theme_likes_dashboard_menu');
function theme_likes_dashboard_menu() {
	add_submenu_page(
		'edit.php', // Inside Posts menu
		'Статистика лайков',
		'Лайки',
		'edit_posts',
		'theme-likes',
		'theme_likes_dashboard_page'
	);
}

function theme_likes_dashboard_page() {
	echo '<div class="wrap"><h1>Статистика лайков</h1></div>';
}

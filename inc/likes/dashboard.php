<?php
// main menu item
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

// page template
function theme_likes_dashboard_page() {
	$table = new Theme_Likes_List_Table();
	$table->prepare_items();

	echo '<div class="wrap">';
	echo '<h1>Статистика лайков</h1>';

	$table->display();

	echo '</div>';
}

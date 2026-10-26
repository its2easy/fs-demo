<?php
if (!class_exists('WP_List_Table')) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

// https://wp-kama.ru/function/wp_list_table
class Theme_Likes_List_Table extends WP_List_Table {
	public function __construct() {
		parent::__construct([
			'singular' => 'post',
			'plural'   => 'posts',
		]);
	}

	public function get_columns() {
		return [
			'title'    => 'Статьи',
			'likes'    => 'Количество лайков',
			'dislikes' => 'Количество дизлайков',
		];
	}

	public function prepare_items() {
		$per_page     = 20;
		$current_page = $this->get_pagenum();

		$query = new WP_Query([
			'post_type'      => 'post',
			'post_status'    => 'any',
			'posts_per_page' => $per_page,
			'paged'          => $current_page,
		]);
		$this->items = $query->posts;

		$this->_column_headers = [$this->get_columns(), [], []]; // important, columns list is empty without it

		$this->set_pagination_args([
			'total_items' => $query->found_posts,
			'per_page'    => $per_page,
		]);
	}

	protected function column_title($post) {
		return '<a href="' . esc_url(get_edit_post_link($post->ID)) . '">' . esc_html(get_the_title($post)) . '</a>';
	}

	// sum of the likes for a post
	protected function column_likes($post) {
		return '0';
	}

	// sum of the dislikes for a post
	protected function column_dislikes($post) {
		return '0';
	}
}

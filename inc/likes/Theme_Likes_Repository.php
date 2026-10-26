<?php
class Theme_Likes_Repository {
	public static function table_name(): string {
		global $wpdb;
		return $wpdb->prefix . 'likes';
	}

	// find an existing vote
	public static function get_vote(int $post_id, string $user_ip): ?Theme_Like_Action {
		global $wpdb;

		$vote = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT action_type FROM %i WHERE post_id = %d AND user_ip = %s',
				self::table_name(),
				$post_id,
				$user_ip
			)
		);
		return $vote ? Theme_Like_Action::tryFrom($vote) : null;
	}

	// set a new vote or replace an existing vote with different action_type.
	// Returns false on sql error ($wpdb->last_error), true otherwise.
	public static function set_vote(int $post_id, string $user_ip, Theme_Like_Action $action_type, string $page_url): bool {
		global $wpdb;

		return (bool)$wpdb->replace(
			self::table_name(),
			[
				'post_id'     => $post_id,
				'user_ip'     => $user_ip,
				'action_type' => $action_type->value,
				'page_url'    => $page_url,
				'created_at'  => current_time('mysql'),
			],
			['%d', '%s', '%s', '%s', '%s']
		);
	}

	// remove an existing vote. Returns false on sql error only — 0 deleted rows can be a parallel removal
	public static function remove_vote(int $post_id, string $user_ip): bool {
		global $wpdb;

		return $wpdb->delete(
			self::table_name(),
			[
				'post_id' => $post_id,
				'user_ip' => $user_ip,
			],
			['%d', '%s']
		) !== false;
	}

	// get one votes number for a post: likes minus dislikes. Used like fallback as it requires an sql query for each post
	public static function get_sum(int $post_id): int {
		global $wpdb;

		$sum = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COALESCE(SUM(action_type = 'like'), 0) - COALESCE(SUM(action_type = 'dislike'), 0)"
				. " FROM %i WHERE post_id = %d",
				self::table_name(),
				$post_id
			)
		);
		return (int)$sum;
	}

	/**
	 * Likes and dislikes sums for the given posts, keyed by post id.
	 * Posts without votes are omitted.
	 *
	 * @param int[] $post_ids Post ids, e.g. [64, 73]
	 *
	 * @return array<int, array{likes: int, dislikes: int}> like [64 => ['likes' => 2, 'dislikes' => 1]]
	 */
	public static function get_votes_summary(array $post_ids): array {
		global $wpdb;

		$post_ids = array_filter(array_map('absint', $post_ids));
		$post_ids = array_values($post_ids);
		if (!$post_ids)  return [];

		$placeholders = implode(',', array_fill(0, count($post_ids), '%d'));
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT post_id, SUM(action_type = 'like') AS likes, SUM(action_type = 'dislike') AS dislikes"
				. " FROM %i WHERE post_id IN (" . $placeholders . ") GROUP BY post_id",
				array_merge([self::table_name()], $post_ids)
			),
			ARRAY_A
		);

		$result = [];
		foreach ($rows as $row) {
			$result[(int)$row['post_id']] = [
				'likes'    => (int)$row['likes'],
				'dislikes' => (int)$row['dislikes'],
			];
		}
		return $result;
	}
}

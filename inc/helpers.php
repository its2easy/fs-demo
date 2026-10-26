<?php

/**
 * Theme pagination
 *
 * @param array $args {
 *     Optional. Arguments to customize output.
 *
 *     @type WP_Query  $wp_query  Query to build pagination for.
 *     @type array     $fake {
 *          Array with fake numbers to test the pagination
 *
 *          @type string $current Current page
 *          @type string $total   Total pages
 *     }
 *     @type boolean $print False to return the markup instead of echo it (default true)
 * }
 * @return void|string
 */
function theme_the_pagination(array $args = []) {
	$is_fake = isset($args['fake']);
	$print   = !isset($args['print']) || $args['print'];
	if ($is_fake || $args['wp_query']->max_num_pages > 1) {
		$current = ($is_fake) ? $args['fake']['current'] : max(1, get_query_var('paged'));
		$current = intval($current);
		$total   = ($is_fake) ? $args['fake']['total'] : $args['wp_query']->max_num_pages;
		$total   = intval($total);

		$arrow = theme_inline_svg('arrow-left.svg');

		// arrows are for the mobile version, labels are for the desktop (768px+), design uses both in figma
		$prev_text = '<span class="theme-pagination__label">Назад</span><span class="theme-pagination__arrow theme-pagination__arrow_prev">' . $arrow . '</span>';
		$next_text = '<span class="theme-pagination__label">Вперед</span><span class="theme-pagination__arrow theme-pagination__arrow_next">' . $arrow . '</span>';

		$pagination = paginate_links(
			[
				'base'      => str_replace(999999999, '%#%', get_pagenum_link(999999999)),
				'format'    => '',
				'add_args'  => false,
				'current'   => $current,
				'total'     => $total,
				'prev_next' => true,
				'prev_text' => $prev_text,
				'next_text' => $next_text,
				'type'      => 'array',
				'end_size'  => 3,
				'mid_size'  => 0,
			]
		);

		$markup = '<ul class="theme-pagination__list">';
		foreach ($pagination as $item) {
			$markup .= '<li class="theme-pagination__item">' . $item . '</li>';
		}
		$markup .= '</ul>';

		// remove page/1/ for the 1st page
		$markup = str_replace('page/1/', '', $markup);

		$markup = str_replace('<a class="prev page-numbers"', '<a class="prev page-numbers" aria-label="Назад"', $markup);
		$markup = str_replace('<a class="next page-numbers"', '<a class="next page-numbers" aria-label="Вперед"', $markup);

		$final_markup = '<nav class="theme-pagination">' . $markup . '</nav>';

		if ($print) {
			echo $final_markup;
		} else {
			return $final_markup;
		}

	}
}

/**
 * Gets image url
 *
 * @param string $name Path relative to assets/img folder
 *
 * @return string Full url of the image
 */
function get_img(string $name): string {
	return get_template_directory_uri() . '/assets/img/' . $name;
}

/**
 * Returns the content of the given svg file
 *
 * @param string $path - File name relative to /assets/img/ folder
 * @return string
 */
function theme_inline_svg(string $path): string {
	$svg_path = get_stylesheet_directory() . '/assets/img/' . $path;

	if (file_exists($svg_path)) {
		return file_get_contents($svg_path);
	} else {
		return '';
	}
}

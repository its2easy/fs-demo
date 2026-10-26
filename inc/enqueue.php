<?php
// Theme assets

add_action('wp_enqueue_scripts', 'theme_enqueue_assets');
function theme_enqueue_assets() {
	$css_path = get_template_directory() . '/assets/main.css';
	$js_path  = get_template_directory() . '/assets/main.js';

	wp_enqueue_style(
		'theme-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'theme-main-css',
		get_template_directory_uri() . '/assets/main.css',
		array(),
		filemtime( $css_path )
	);

	wp_enqueue_script( 'theme-main-js',
		get_template_directory_uri() . '/assets/main.js',
		array(),
		filemtime( $js_path ),
		true
	);

	wp_localize_script('theme-main-js', 'phpData',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		)
	);
}

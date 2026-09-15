<?php
/**
 * Where Food Takes Us — theme functions
 *
 * Deliberately minimal. Block themes push almost everything into
 * theme.json; functions.php is just for setup calls, nav menu
 * registration, and (later) the small custom features that replace
 * JetEngine/Spectra functionality.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

/**
 * Core theme supports. Block themes get most of this for free from
 * theme.json, but a few things still need an explicit add_theme_support().
 */
function wftu_theme_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'wherefoodtakesus' ),
		)
	);
}
add_action( 'after_setup_theme', 'wftu_theme_setup' );


function wftu_enqueue_search_overlay() {
	wp_enqueue_style(
		'wftu-search-overlay',
		get_theme_file_uri( 'assets/css/site.css' ),
		array(),
		wp_get_theme()->get( 'Version' )
	);
	wp_enqueue_script(
		'wftu-search-overlay',
		get_theme_file_uri( 'assets/js/search-overlay.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'wftu_enqueue_search_overlay' );
/**
 * Custom block pattern category, for organizing patterns you build
 * later (hero sections, post-footer CTAs, etc.) instead of dumping
 * them all into the default "Featured" category.
 */
function wftu_register_pattern_categories() {
	register_block_pattern_category(
		'wherefoodtakesus',
		array( 'label' => __( 'Where Food Takes Us', 'wherefoodtakesus' ) )
	);
}
add_action( 'init', 'wftu_register_pattern_categories' );


function wftu_initials_avatar( $avatar, $id_or_email, $args ) {
	$author_name = '';

	if ( $id_or_email instanceof WP_Comment ) {
		$author_name = $id_or_email->comment_author;
	} elseif ( is_numeric( $id_or_email ) ) {
		$user        = get_userdata( $id_or_email );
		$author_name = $user ? $user->display_name : '';
	} elseif ( is_string( $id_or_email ) ) {
		$author_name = $id_or_email;
	}

	$initial = $author_name ? mb_strtoupper( mb_substr( trim( $author_name ), 0, 1 ) ) : '?';

	return sprintf(
		'<span class="wftu-avatar-initial"><span class="wftu-avatar-initial__letter">%s</span></span>',
		esc_html( $initial )
	);
}
add_filter( 'get_avatar', 'wftu_initials_avatar', 10, 3 );

function wftu_enqueue_faq_accordion() {
	if ( ! is_singular() || ! has_block( 'yoast/faq-block' ) ) {
		return;
	}
	wp_enqueue_script(
		'wftu-faq-accordion',
		get_theme_file_uri( 'assets/js/faq-accordion.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'wftu_enqueue_faq_accordion' );

function wftu_add_table_of_contents( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	preg_match_all( '/<h2([^>]*)>(.*?)<\/h2>/is', $content, $matches, PREG_SET_ORDER );

	if ( count( $matches ) < 2 ) {
		return $content; // Not enough sections to make a TOC worthwhile.
	}

	$toc_items  = array();
	$used_slugs = array();

	$content = preg_replace_callback(
		'/<h2([^>]*)>(.*?)<\/h2>/is',
		function ( $match ) use ( &$toc_items, &$used_slugs ) {
			$attrs = $match[1];
			$inner = $match[2];
			$text  = wp_strip_all_tags( $inner );

			// Respect a manually-set HTML anchor rather than overriding it.
			if ( preg_match( '/\bid=["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
				$slug = $id_match[1];
			} else {
				$base_slug = sanitize_title( $text );
				$slug      = $base_slug;
				$i         = 2;
				while ( in_array( $slug, $used_slugs, true ) ) {
					$slug = $base_slug . '-' . $i;
					$i++;
				}
				$attrs .= ' id="' . esc_attr( $slug ) . '"';
			}

			$used_slugs[] = $slug;
			$toc_items[]  = array( 'slug' => $slug, 'text' => $text );

			return '<h2' . $attrs . '>' . $inner . '</h2>';
		},
		$content
	);

	$list_items = '';
	foreach ( $toc_items as $item ) {
		$list_items .= sprintf(
			'<li><a href="#%s">%s</a></li>',
			esc_attr( $item['slug'] ),
			esc_html( $item['text'] )
		);
	}

	$toc_html = '<div class="wftu-toc">'
		. '<button type="button" class="wftu-toc__toggle" aria-expanded="false" aria-controls="wftu-toc-list">'
		. '<span class="wftu-toc__label">In This Post</span>'
		. '<span class="wftu-toc__action">Show <span class="wftu-toc__icon">+</span></span>'
		. '</button>'
		. '<ol id="wftu-toc-list" class="wftu-toc__list" hidden>' . $list_items . '</ol>'
		. '</div>';

	$first_h2_pos = strpos( $content, '<h2' );
	if ( false !== $first_h2_pos ) {
		$content = substr_replace( $content, $toc_html, $first_h2_pos, 0 );
	}

	return $content;
}
add_filter( 'the_content', 'wftu_add_table_of_contents', 20 );

function wftu_enqueue_toc_script() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	wp_enqueue_script(
		'wftu-toc',
		get_theme_file_uri( 'assets/js/toc.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'wftu_enqueue_toc_script' );
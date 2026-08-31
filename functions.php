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

/**
 * ---------------------------------------------------------------
 * TODO — things to port over from the old child theme as each
 * migration phase completes. Left as empty stubs on purpose so
 * nothing is silently forgotten.
 * ---------------------------------------------------------------
 */

// Phase: Migrate structured data from JetEngine to ACF
// -> register the Locations custom post type + ACF field group here
//    (or in a small mu-plugin / custom plugin, which is arguably the
//    better home for anything that stores data, so it survives a
//    future theme change).

// Phase: Rebuild the interactive map display
// -> enqueue the Google Maps JS API + a small custom block reading
//    the ACF Google Map field, replacing JetEngine's Map Listing.

// Phase: Remove Spectra
// -> the old render_block filter that swapped Spectra's social-share
//    block to use Yoast's stored OG image was specific to Spectra's
//    block markup. Once Spectra's block is replaced, that filter goes
//    away entirely rather than being ported — the replacement block
//    should just pull the Yoast OG image directly.

// Phase: QA / performance
// -> re-add the featured-image preload tag (the one that took LCP
//    from 6,243ms to 460ms) once the single.html template above is
//    finalized — it hooks into wp_head and targets get_post_thumbnail_id().

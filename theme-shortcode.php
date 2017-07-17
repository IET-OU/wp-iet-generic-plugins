<?php namespace IET_OU\WP_Generic_Plugins;

/**
 * Plugin Name: Theme shortcode
 * Plugin URI:
 * Description: Shortcode to insert images from a theme, via shortcode - ` [theme alt=".." src="example/image.png"] `
 * Author:      Nick Freear
 * Author URI:  https://github.com/nfreear
 * Version:     1.0-alpha
 *
 * @copyright   © 2017 The Open University (UK).
 * @author      Nick Freear, 24 January 2017.
 */

// http://wordpress.stackexchange.com/questions/66026/how-do-i-reference-the-theme-path-in-pages-for-images

class Theme_Shortcode_Plugin {

	const SHORTCODE = 'theme';
	const PATH = '/wp-content/themes/tttt-guide/images';
	const TPL_IMAGE = '<img class="theme-sc x" src="%s/images/%s" alt="%s" />';

	public function __construct() {
		add_shortcode( self::SHORTCODE, [ &$this, 'shortcode' ] );
	}

	public function shortcode( $attrs = [], $content = null ) {
		$inp = (object) shortcode_atts( [
				'src' => null,
				'alt' => null,
		], $attrs );

		$theme_uri = is_child_theme()
				? get_stylesheet_directory_uri()
				: get_template_directory_uri();

		return sprintf( self::TPL_IMAGE, $theme_uri, $inp->src, $inp->alt );
	}
}
$plugin = new Theme_Shortcode_Plugin();

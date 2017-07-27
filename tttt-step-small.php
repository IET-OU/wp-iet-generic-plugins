<?php namespace IET_OU\WP_Generic_Plugins;

/*
 Plugin Name: TTTT Small Guide Step
 Plugin URI:
 Description: Shortcode for Tricky Topics Guide - ` [tttt_small step="1"] Title [/tttt_small] `
 Author:      RF
 Author URI:  -
 Version:     1.0

 @copyright   © 2017 The Open University (UK).
 @author      RF, 18 July 2017.
*/

class TTTT_Small_Step_Plugin {

	const SHORTCODE = 'tttt_small';
	const IMAGE_URL = '/images/guide/small-guide-step-%s.jpg';
	const TPL = '<div id="step-%s" class="tttt-small-step"><img src="%s" title="Step: %s" /><div><ol start="%s"><li>%s</li></ol></div></div>';

	public function __construct() {
		add_shortcode( self::SHORTCODE, [ &$this, 'shortcode_tttt_small' ] );
	}

	public function shortcode_tttt_small( $attrs = [], $content = null ) {
		$inp = (object) shortcode_atts( [
			'step'  => null,
			'page_link'  => null,
		], $attrs );

		$a1 = $inp->page_link == null ? '' : '<a href="/' . $inp->page_link . '/">';
		$a2 = $inp->page_link == null ? '' : '</a>';

		$image_url = get_stylesheet_directory_uri() . sprintf( self::IMAGE_URL, $inp->step );

		return $a1 . sprintf( self::TPL, $inp->step, $image_url, $inp->step, $inp->step, $content ) . $a2;
	}

}
$plugin = new TTTT_Small_Step_Plugin();

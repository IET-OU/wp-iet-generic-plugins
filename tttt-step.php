<?php namespace IET_OU\WP_Generic_Plugins;

/*
 Plugin Name: TTTT Guide Step
 Plugin URI:
 Description: Shortcode for Tricky Topics Guide - ` [tttt step="3-2"] Some text ... [/tttt] `
 Author:      Nick Freear
 Author URI:  https://github.com/nfreear
 Version:     1.0-alpha

 @copyright   © 2016 The Open University (UK).
 @author      Nick Freear, 12 December 2016.
*/

class TTTT_Step_Plugin {

	const SHORTCODE = 'tttt';
	const IMAGE_URL = '/images/guide/guide-step-%s.jpg';
	const TPL = '<div id="step-%s" class="tttt-step"><img src="%s" title="Step: %s" /><div>%s</div></div>';

	public function __construct() {
		add_shortcode( self::SHORTCODE, [ &$this, 'shortcode_tttt' ] );
	}

	public function shortcode_tttt( $attrs = [], $content = null ) {
		$inp = (object) shortcode_atts( [
			'step'  => null,
		], $attrs );

		$image_url = get_stylesheet_directory_uri() . sprintf( self::IMAGE_URL, $inp->step );

		return sprintf( self::TPL, $inp->step, $image_url, $inp->step, $content );
	}

}
$plugin = new TTTT_Step_Plugin();

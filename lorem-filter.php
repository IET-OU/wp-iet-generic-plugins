<?php namespace IET_OU\WP_Generic_Plugins;

/*
 Plugin Name: Lorem Filter
 Plugin URI:
 Description: ...
 Author:      Nick Freear
 Author URI:  https://github.com/nfreear
 Version:     1.0-alpha

 @copyright © 2017 The Open University (UK).
 @author    Nick Freear, 04 January 2017.
 @link      https://en-gb.wordpress.org/plugins/lorem-shortcode/
 @link      https://wordpress.org/plugins/lorem-ipsum-dummy-article-shortcode
*/

#require_once __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use SoderlindLorem as SoderlindLorem;

class Lorem_Filter_Plugin {

	public function __construct() {
		add_filter( 'the_content', [ &$this, 'filter_the_content' ]);
		add_filter( 'lorem_small', [ &$this, 'filter_lorem_small' ]);
	}

	public function filter_lorem_small( $var_paras = null ) {
        $var_paras = $var_paras > 1 ? $var_paras : 1;

		if ( class_exists( 'SoderlindLorem' ) ) {

			$soder_plugin = new SoderlindLorem();
			return $soder_plugin->lorem( [
				'p' => self::env( 'SM_PARAS', $var_paras ),  # shortcode default: 5.
				'l' => self::env( 'SM_LINES', 3 ),  #   "  default: 3.
			] );
		}
		return '';
	}

	public function filter_the_content( $content ) {

		if ( ! $content && class_exists( 'SoderlindLorem' ) ) {

            if ( class_exists( 'Dotenv\Dotenv' )) {
			    $dotenv = new Dotenv( __DIR__ . '/../../../' );
			    $dotenv->load();
		    }

			$soder_plugin = new SoderlindLorem();
			$lorem = $soder_plugin->lorem( [
				'p' => self::env( 'PARAS', 5 ),  # shortcode default: 5.
				'l' => self::env( 'LINES', 6 ),  #   "  default: 3.
			] );

			$lorem = preg_replace( '@<\/p>@', '</p> <h2> Lorem heading </h2> ', $lorem, self::env( 'LIMIT', 1 ) );
			$lorem = preg_replace( '@,\.@', '.', $lorem );

			return $lorem;
		}

		return $content;
	}

	protected static function env( $key, $default = null ) {
		return getenv( "LOREM_$key" ) ? getenv( "LOREM_$key" ) : $default;
	}

}
$plugin = new Lorem_Filter_Plugin();

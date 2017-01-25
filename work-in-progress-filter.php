<?php namespace IET_OU\WP_Generic_Plugins;

/**
 * Plugin Name: Work-in-progress
 * Plugin URI:
 * Description: Filter to display configurable text, eg. "Under construction" in empty pages / posts.
 * Author:      Nick Freear
 * Author URI:  https://github.com/nfreear
 * Version:     1.0-alpha
 *
 * @copyright   © 2017 The Open University (UK).
 * @author      Nick Freear, 19 January 2017.
 * @link        https://en-gb.wordpress.org/plugins/lorem-shortcode/
 */

class Work_In_Progress_Filter_Plugin {

	public function __construct() {
		add_filter( 'the_content', [ &$this, 'filter_the_content' ] );
	}


	public function filter_the_content( $content ) {

		if ( ! $content ) {

			$page_slug = defined( 'WORK_IN_PROGRESS_SLUG' ) ? WORK_IN_PROGRESS_SLUG : 'work-in-progress';

			$wp_post = get_page_by_path( $page_slug );

			return '<div class="' . $page_slug . '">' . $wp_post->post_content . '</div>';
		}

		return $content;
	}

}
$plugin = new Work_In_Progress_Filter_Plugin();

<?php
/*
Plugin Name: IET WP Options
Plugin URI:  https://github.com/IET-OU/oer-evidence-hub-org
Description: Display PHP info and the WordPress options table to administrators [LACE].
Author:      Nick Freear [IET-OU]
Author URI:  https://github.com/nfreear
Version:     0.1
*/
// NDF, 6 Oct 2014.


class IET_WP_Options_Plugin {

    const MENU_SLUG = 'iet-wp-options';
	const CAPABILITY = 'manage_options';

    public function __construct() {

        if ( is_admin() ) {
            add_action( 'admin_menu', array( $this, 'add_options_pages' ));
        }
    }

    /** WP action: 'admin_menu' */
    public function add_options_pages() {

        // This page will be under "Settings"
        $hook_suffix = add_options_page(
            'PHP info',
            'PHP info',
            self::CAPABILITY,
            self::MENU_SLUG . '-phpinfo',
            array( &$this, 'create_phpinfo_page' )
        );
        $hook_suffix = add_options_page(
            'WP Options',
            'WP Options',
            self::CAPABILITY,
            self::MENU_SLUG,
            array( &$this, 'create_options_page' )
        );
        $hook_suffix = add_options_page(
            'oEmbed info',
            'oEmbed info',
            self::CAPABILITY,
            self::MENU_SLUG . '-oembed',
            array( &$this, 'create_oembed_info_page' )
        );
    }

    /** Callback to create a phpinfo() page. */
    public function create_phpinfo_page() {
        ob_start();

        phpinfo();

        $page = ob_get_clean();
        $page = str_ireplace(array(
            '<html>','<head>','<body>', '</html>','</head>','</body>' ), '', $page );
        $page = str_replace( '<title>phpinfo()</title>', '', $page );
        $page = preg_replace( '#<!DOCTYPE.*?>#smi', '', $page );

        $this->phpinfo_style( $page );

        $page = preg_replace( '#(<style.*?\/style>)#smi', '', $page );

        echo '<div id="iet-phpinfo">'. $page .'</div>';
    }

    /** Callback to display the contents of the WP options table. */
    public function create_options_page() {

        // Cached (non-cached: get_alloptions() )
        $options = wp_load_alloptions();

        echo '<pre id="iet-wp-options">';
        print_r( $options );
        echo '</pre>';
    }

    public function create_oembed_info_page() {
        $wp_oembed = _wp_oembed_get_object();

        $oembed_count = $this->get_oembed_cache();
        ?>

        <div id=iet-wp-oembed >
        <p>Count of oEmbed records in 'postmeta' table: <?php echo $oembed_count ?>
        <p>Count of oEmbed provider entries: <?php echo count( $wp_oembed->providers ) ?>

        <h2> oEmbed providers </h2>
        <table>
            <tr><th> Format </th><th> Provider </th><!--<th> Regex? </th>--></tr>

        <?php foreach ( $wp_oembed->providers as $fmt => $prov ): ?>
            <tr><td><?php echo $fmt ?></td> <td><?php echo $prov[ 0 ] ?></td></tr>
        <?php endforeach; ?>
        </table>
        </div>

<?php
    }

    protected function get_oembed_cache( $count_only = true ) {
        global $wpdb;

        if ($count_only) {
            $cache = $wpdb->get_var(
                "SELECT COUNT(*) FROM wp_postmeta WHERE meta_key LIKE '%_oembed%'" );
        } else {
            $cache = $wpdb->get_results(
                "SELECT * FROM wp_postmeta WHERE meta_key LIKE '%_oembed%'" );
        }
        return $cache;
    }

    protected function phpinfo_style( $page ) {
        preg_match( '#<style.*?>(.+?)<\/style#smi', $page, $matches );
        $style = explode( '}', $matches[ 1 ] );
        $css_out = array();

        foreach ($style as $line) {
            if (trim( $line ) == '') continue;

            $css_out[] = $line . "}\n#iet-phpinfo ";
        }

        echo "\n<style id='iet-phpinfo-css'>\n#iet-phpinfo";
        echo implode( '', $css_out );
        echo " .center{font-size: 1.4em;}\n</style>\n";
    }
}
$iet_wp_options = new IET_WP_Options_Plugin();

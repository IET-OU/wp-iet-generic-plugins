<?php namespace IET_OU\WP_Generic_Plugins;
/*
Plugin Name: Simple Menu
Plugin URI:
Description: Display a menu or sub-menu based on the shortcode `[simple_menu menu=Main sub=Help]` (JuxtaLearn)
Author:      Nick Freear
Author URI:  https://github.com/nfreear
Version:     1.2-alpha
*/

// Copyright © 2015-2016 The Open University (UK).

// NDF, 21 May 2014.
// NDF/Nick Freear, 29 November 2016 - 'display_body()'

/*
  Shortcode:  [simple_menu menu=Main sub=Help content=false]
*/


class Simple_Menu {

  const SHORTCODE = 'simple_menu';

  public function __construct() {
	  add_shortcode( self::SHORTCODE, [ &$this, 'shortcode_simplemenu' ] );
  }


  public function shortcode_simplemenu( $attr ) {
    extract( shortcode_atts( [
        'menu' => 'Main',
        'sub'  => NULL,
        'content' => false,
    ], $attr ) );

    $menu_obj = wp_get_nav_menu_object( $menu ); //232 ); //'Help' ); //'Main' );
    $menu_items = wp_get_nav_menu_items( $menu_obj );

    if ( ! $menu_items ) return;

    $parent_id = NULL;
    $sub_menu = array();
    if ($sub) {
      foreach ($menu_items as $item) {
        if ($sub == $item->title) {
          $parent_id = $item->ID;
        }
        elseif ($parent_id == $item->menu_item_parent) {
          $sub_menu[] = $item;
        }
      }
    } else {
      $parent_id = $sub = 'Z';
      $sub_menu = $menu_items;
    }

    $classes = self::SHORTCODE . " menu-$menu sub-$sub";

    if ($content) {
      return $this->display_content( $sub_menu, $parent_id, $classes );
    }
    return $this->display_menu( $sub_menu, $parent_id, $classes );
  }


  protected function display_menu( $sub_menu, $menu_id, $classes ) {
    ob_start();

    ?><ul class="<?php echo $classes ?>" id="sm-menu-<?php echo $menu_id ?>">
  <?php foreach ($sub_menu as $it): ?>
    <li id="menu-item-<?php echo $it->ID ?>" class="<?php self::echo_classes( $it ) ?>"
      ><a href="<?php echo $it->url ?>"><?php echo $it->title ?></a>
  <?php endforeach; ?>
    </ul><?php

    $this->end();

    return ob_get_clean();
  }


  protected function display_content( $sub_menu, $menu_id, $classes ) {
    ob_start();

    /* ?><ul class="<?php echo $classes ?>" id="sm-menu-<?php echo $menu_id ?>">
  <?php */ foreach ($sub_menu as $it): ?>
    <div id="menu-item-<?php echo $it->object_id ?>" class="<?php self::echo_classes( $it, true ) ?>"
      data-post='<?php self::post_json( $it ) ?>'>
      <h2 class="item-title"><?php echo $it->title ?></h3>
      <?php //$page = get_page($it->object_id);?>
      <?php echo apply_filters('the_content', get_post_field( 'post_content', $it->object_id )); ?>
      <?php //var_dump( $it ); exit; ?>
    </div>
  <?php endforeach; /* ?>
    </ul><?php */

    $this->end();

    return ob_get_clean();
  }


  protected function end( $shortcode = NULL ) {
    $shortcode = $shortcode ? $shortcode : get_class($this);
    $shortcode = str_replace('\\', '-', $shortcode);  // Handle PHP namespaces.
    ?>
  <script>
  document.documentElement.className += " jxl-shortcode <?php echo $shortcode ?>";
  </script>
<?php
  }

  protected static function echo_classes( $menu_it, $is_content = false ) {
    $classes = self::get_option( 'iet_simple_menu_item_class', '' );
    echo "menu-item $classes " . ( $is_content ? 'content ' : '' ) . implode(' ', $menu_it->classes);
  }

  protected static function post_json( $menu_obj ) {
    echo json_encode([
      'cls'   => get_class($menu_obj),
      'nav_ID'   => $menu_obj->ID,
      'post_id' => $menu_obj->object_id,
      'type' => $menu_obj->object,
      # 'post_type' => $menu_obj->post_type, # 'nav_menu_item'
      'modified'  => $menu_obj->post_modified_gmt,
      'url'  => $menu_obj->url,
      'author_id' => $menu_obj->post_author,
      'author' => get_the_author_meta( 'nickname', $menu_obj->post_author ), #Deprecated: get_author_name(..)
    ]);
  }

  protected static function get_option( $key, $default = NULL ) {
    $_KEY = strtoupper( $key );
    $default = /*!$default &&*/ defined( $_KEY ) ? constant( $_KEY ) : $default;
    return get_option( $key, $default );
  }

}
$simple_menu = new Simple_Menu();

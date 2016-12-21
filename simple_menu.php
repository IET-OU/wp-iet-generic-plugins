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
  protected static $_post_id;

  public function __construct() {
	  add_shortcode( self::SHORTCODE, [ &$this, 'shortcode_simplemenu' ] );
  }


  public function shortcode_simplemenu( $attr ) {
    extract( shortcode_atts( [
        'menu' => 'Main',
        'sub'  => NULL,
        'content' => false,
        'comments'=> false,
        'top_link'=> true,
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

    if ( $content ) {
      return $this->display_content( $sub_menu, $parent_id, $classes, $comments, $top_link );
    }
    return $this->display_menu( $sub_menu, $parent_id, $classes );
  }


  public static function post_id() {
    return self::$_post_id;
  }


  protected function display_menu( $sub_menu, $menu_id, $classes ) {
    ob_start();

    ?><ul class="<?php echo $classes ?>" id="sm-menu-<?php echo $menu_id ?>">
  <?php foreach ($sub_menu as $it):
      self::$_post_id = $it->object_id; ?>
    <li id="menu-item-<?php echo $it->ID ?>" class="<?php self::echo_classes( $it ) ?>"
      ><a href="<?php echo $it->url ?>"><?php echo $it->title ?></a>
  <?php endforeach; ?>
    </ul><?php

    $this->end();

    return ob_get_clean();
  }


  protected function display_content( $sub_menu, $menu_id, $classes, $show_comments = false, $with_top_link = false ) {
    ob_start();

    ?><div class="<?php echo $classes ?>" id="sm-menu-<?php echo $menu_id ?>">
  <?php foreach ($sub_menu as $it):
      self::$_post_id = $it->object_id; ?>
    <div id="menu-item-<?php echo $it->object_id ?>" class="<?php self::echo_classes( $it, true ) ?>"
      data-post='<?php self::post_json( $it ) ?>' data-uri='<?php echo $it->url ?>'>
      <h2 class="item-title"><?php echo $it->title ?></h3>
      <?php echo apply_filters('the_content', get_post_field( 'post_content', $it->object_id )) ?>
      <?php self::display_comments( $it->object_id, $show_comments ) ?>
      <?php if ( $with_top_link ): ?>
          <a href="#site-main" class="tttt-to-top">Return to top of page</a>
      <?php endif; ?>
    </div>
  <?php endforeach ?>
    </div><?php

    $this->end();

    return ob_get_clean();
  }


  protected static function display_comments( $post_id = null, $show_comments = false ) {
      //echo $show_comments .' '. comments_open() .' '. get_comments_number();
      if ( ! $show_comments || ! comments_open( $post_id ) ) {
          return;
      }

      self::load_template( 'simple-menu-comments', false );
  }

  // https://codex.wordpress.org/Function_Reference/load_template#Loading_a_template_in_a_plugin.2C_but_allowing_theme_and_child_theme_to_override_template
  protected static function load_template( $name = 'some-template', $require_once = true ) {
      if ( $overridden_template = locate_template( $name . '.php' ) ) {
         // locate_template() returns path to file
         // if either the child theme or the parent theme have overridden the template
         load_template( $overridden_template, $require_once );
      } else {
         // If neither the child nor parent theme have overridden the template,
         // we load the template from the 'templates' sub-directory of the directory this file is in
         load_template( __DIR__ . '/templates/' . $name . '.php', $require_once );
      }
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
      #'url'  => $menu_obj->url,
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

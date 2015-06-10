<?php
/*
Plugin Name: IET WP_Query
Plugin URI:  https://github.com/IET-OU/oer-evidence-hub-org
Description: Shortcode [wp_query] wrapper around WordPress core class: `WP_Query` [LACE]
Author:      Nick Freear [@IET-OU]
Author URI:  https://github.com/IET-OU
Version:     0.2
*/


/**
* @link  https://gist.github.com/nfreear/e78c046784893c5639f3
* @link  http://codex.wordpress.org/Class_Reference/WP_Query
* @copyright Nick Freear, 14 March 2015.
*/


class IET_WP_Query_Plugin {

  const SHORTCODE = 'wp_query';

  const ACTION = 'iet_wp_query_loop';


  public function __construct() {
    add_shortcode( self::SHORTCODE, array( &$this, 'shortcode' ));
  }


  public function shortcode( $attrs, $content = '', $name ) {
    echo '<!--X-' . __CLASS__ . ': '. json_encode( $attrs ) . '-->';

    $attrs = ('' === $attrs) ? array() : $attrs;

    $query = null;
    $format = 'full';

    $post_type = 'post';
    $post_status = 'publish';
    $orderby = 'title';  #'date'
    $order = 'ASC';      #'DESC'
    $posts_per_page = -1;
    $tag = null;

    extract( $attrs );

    $post_type = is_string( $post_type ) ? explode( ',', $post_type ) : $post_type;

    $args = array(
      'query ' => $query,
      'post_type' => $post_type,
      'post_status' => $post_status,
      'orderby' => $orderby,
      'order' => $order,
      'posts_per_page' => $posts_per_page,
      'tag'   => $tag,
      'x_format' => $format,
    );

    $classes = $this->get_classes( $args );

    $args = is_string( $query ) ? $query : $args;

    $the_query = new WP_Query( $args );

    ob_start(); ?>
  <!-- WP_Query SQL: <?php echo $the_query->request ?>; -->
  <div class="<?php echo $classes ?>"><?php

    // The Loop
    if ('full' === $format):
      $this->the_loop_full( $the_query );
    elseif ('richlist' === $format):
      $this->the_loop_richlist( $the_query );
    elseif ('list' === $format):
      $this->the_loop_list( $the_query );
    else:
      do_action( self::ACTION, $the_query, $format );
    endif;

    /* Restore original Post Data */
    wp_reset_postdata();

    ?></div><?php

    return ob_get_clean();
  }


  protected function the_loop_full( $the_query ) {
    // The Loop
    if ( $the_query->have_posts() ):
      while ( $the_query->have_posts() ):
        $the_query->the_post();
        global $more; $more = 0; 
        get_template_part( 'content');
      endwhile;
    else: ?>
      <p class=no-posts-found >No posts found.</p><?php
    endif;
  }

  protected function the_loop_richlist( $the_query ) {
    // The Loop
    if ( $the_query->have_posts() ):
      echo '<ul>';
      while ( $the_query->have_posts() ):
        $the_query->the_post(); ?>
  <li class="type-<?php echo get_post_type()?>"><a href="<?php the_permalink()?>"
  ><?php the_title()?></a> <i><?php the_author()?></i> <time datetime="<?php the_date_xml()?>"
  ><?php echo get_the_date()?></time> <small><?php echo get_post_type()?></small>
<?php
      endwhile;
      echo '</ul>';
    else: ?>
      <p class=no-posts-found >No posts found.</p><?php
    endif;
  }

  protected function the_loop_list( $the_query ) {
    // The Loop
    if ( $the_query->have_posts() ):
      echo '<ul>';
      while ( $the_query->have_posts() ):
        $the_query->the_post(); ?>
      <li><a href="<?php the_permalink() ?>"><?php the_title() ?></a></li>
<?php
      endwhile;
      echo '</ul>';
    else: ?>
      <p class=no-posts-found >No posts found.</p><?php
    endif;
  }


  protected function get_classes( $attrs ) {
    $classes[] = self::SHORTCODE;  //'shortcode-'..
    $classes[] = str_replace( '_', '-', strtolower( __CLASS__ )); 
    if ($attrs) {
      foreach ($attrs as $key => $value) {

        if (!$value) continue;

        if (is_string( $value )) {
          $classes[] = $key .'-'. $value;
        } else {
          //sort( $value, SORT_NATURAL );
          sort( $value );
          $classes[] = $key .'-'. implode( '-', $value );
        }
      }
    }
    return esc_attr(implode( ' ', $classes ));
  }

}
$iet_wp_query = new IET_WP_Query_Plugin();


#End.

<?php
/*
Plugin Name: LACE Javascript
Plugin URI:  https://github.com/IET-OU/oer-evidence-hub-org
Description: Fix error page etc. for the WordPress site run by IET at The Open University [LACE] [Bug: #27]
Author:      Nick Freear [@IET-OU]
Author URI:  https://github.com/IET-OU/
Version:     0.1
*/

/**
* @copyright Nick Freear, 21 October 2014.
*/

class Lace_Javascript_Plugin {

  public function __construct() {
    add_action( 'wp_footer', array( &$this, 'wp_footer_javascript' ), 99);
    add_action( 'wp_footer', array( &$this, 'text_hover_qtip_javascript' ), 100);
    add_action( 'admin_footer', array( &$this, 'admin_footer_javascript' ));

    add_action( 'wp_enqueue_scripts', array( &$this, 'front_enqueue_scripts' ));
  }

  public function front_enqueue_scripts() {
    wp_enqueue_script( 'lace-javascript', plugins_url(
      'js/lace-javascript.js', __FILE__
    ), array( 'jquery' ), false, $in_footer = TRUE );
  }


  public function admin_footer_javascript() { ?>
  <script id="lace-eh-form-js">
  /*
    Evidence-Hub form edits.
  */
  jQuery(function ($) {
    $("#evidence_hub_sector, #evidence_hub_hypothesis_id").attr({
        required: "required",
        "aria-required": true
    });
    $("._NOT_REQUIRED_label[ for = evidence_hub_sector ]")
        .addClass("required")
        .append("<i>*</i>") ;

  });
  </script>
<?php
  }

  /** Javascript to fix the "Not found" message for evidence form [Bug: #27].
  */
  public function wp_footer_javascript() { ?>

<script id="lace-javascript">
jQuery(function ($) {

  var
    is_private_page =
      document.location.href.match(/(page_id=114|contribute\/evidence-form)/),
    $not_found = $(".error404, .not-found"),
    $not_found_p = $not_found.find("p").first(),
    is_not_found = $not_found.length > 0,
    $private_link =
      $("article a[href *= 'page_id=114'], article a[href *= evidence-form]"),
    icon_lock =
    '<span class="icon-webfont el-icon-lock"></span><span class="icon-webfont el-icon-user"></span>',
    referrer = document.referrer,
    W = window;

  $private_link.append(icon_lock).attr("title", "Login required");

  if (is_not_found && is_private_page) {

    $(".entry-title").html(icon_lock +
      " Sorry, you don't have permission to view this page");
    $(".entry-content p:first").html(
      "If you think you should have access, please contact us.");
    $("title").html($("title").html().replace(/.+\|/, "Unauthorized |"));
    $("body").addClass("lace-js-401");

    W.console && console.log("lace_js - Actual error: 401 Unauthorized");
  }
  else if (is_not_found) {  // [Bug: #4]
    W.console && console.log("History", window.history);

    if (referrer && referrer !== W.location) {
      $not_found_p.before(
        "<button type='button' onclick='history.back(-1)'>← Back</button>");

      W.console && console.log("Referrer:", referrer.split("/")[2]);
    }

    $not_found_p.text(
      $not_found_p.text().replace(/find a related post/, "find a related page"));
  }

  W.console && console.log("lace_js", is_not_found, is_private_page, referrer);
});
</script>

<?php
  }


  /** qTip JS for `text-hover` WP plugin - fix UI [LACE]+ [Bug: #5]
  */
  public function text_hover_qtip_javascript() {
    if (!class_exists( 'c2c_TextHover' )):
      ?><!--lace-js plugin: c2c_TextHover not found.--><?php
      return;
    endif;

    $th_opts = (object) get_option( 'c2c_text_hover' );

    if (isset( $th_opts->use_pretty_tooltips ) && $th_opts->use_pretty_tooltips) {
      return;
    }

    $th_assets_url = plugins_url( 'text-hover' ) . '/assets/';
    ?>
<link rel=stylesheet href="<?php echo $th_assets_url ?>jquery.qtip.min.css" />
<script src="<?php echo $th_assets_url ?>jquery.qtip.min.js"></script>
<script id="lace-js-text-hover" >
jQuery(function ($) {

  $.fn.qtip && $( 'acronym.c2c-text-hover[title!=""]' ).qtip({
	style: { classes: 'x-text-hover-qtip qtip-bootstrap' },
	show: { solo: true, modal: false },
	hide: {
	  delay: 2500  // Milliseconds.
	  //distance: 20  // Pixels.
	}
  });

  //console.log($.fn.qtip);
  //var text_hover_opts = <?php #echo json_encode( $th_opts ) ?>;
});
</script>

<?php
  }

}
$lace_javascript = new Lace_Javascript_Plugin();


#End.

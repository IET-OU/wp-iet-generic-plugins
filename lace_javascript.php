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
    add_action( 'wp_footer', array( &$this, 'wp_footer_javascript' ));
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

}
$lace_javascript = new Lace_Javascript_Plugin();


#End.

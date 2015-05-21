/*!
  Various Javascript fixes/hacks for LACE (inc. CleanPrint customizations)
*/

jQuery(function ($) {

  var W = window
    , C = W.console
    , has_cleanprint = W.WpCpCleanPrintPrintHtml || W.CleanPrintHtml
    , $inject_cleanprint = $("body") //.search-results, body.single-hypothesis")
    , clearprint_post_re = /(search-results|postid-\d+)/
    , cleanprint_exclude_sel = "#cookie-notice, .nav-menu, .assistive-text, .jxl-message, .oer-chart-loading, .leaflet-control-container, .hyp-tpl-hint"
    , cleanprint_include_sel = ".page-title, .entry-title, #X-primary"
    , vis_table_row_sel = ".hypothesis #country-vis-table tbody tr";


  /* Fix for blank country-cell in [hypothesis_geosummary] table [Bug: #6]
  */
  when_call(function () {
    return $(vis_table_row_sel).length;
  },
  function () {
    var $vis_table_row = $(vis_table_row_sel);

    C && console.log("Vis table:", $vis_table_row);

    $vis_table_row.each(function () {

      var $row = $(this)
        , $cell_1 = $row.find("td").first();

      if ("" === $cell_1.text()) {
        $cell_1.html("<i>No location given</i>");

        $row.addClass("no-location-row").attr("title", "No location given");
      }

      C && console.log("Row:", $row, $cell_1);
    });

  });


  // Evidence map attribution hack.
  var $map_icon = $(".lace-map-icon");
  if ($map_icon.length) {
    $(".leaflet-bottom.leaflet-left").append($map_icon[0].outerHTML);
  }


  /* CleanPrint customizations [Bug: #9]
  */
  C && console.log("CleanPrint?", has_cleanprint);
  if (has_cleanprint) {

  if ($inject_cleanprint.length) {
    var post_id = $("body").attr("class").match(clearprint_post_re);
    post_id = (post_id && post_id[1]) || "custom";

    $(".page-title, .entry-title").first().append(
    '<div class="lace-cleanprint-buttons"><a href="." onclick="WpCpCleanPrintPrintHtml(\'%s\');return false" title="Print page" class="cleanprint-exclude"><img src="/wp-content/plugins/cleanprint-lt/images/CleanPrintBtn_white.png" alt="Print page"/></a></div>'.replace("%s", post_id));

    C && console.log("Inject CleanPrint:", $inject_cleanprint);
  }

  $(cleanprint_exclude_sel).addClass("cleanprint-exclude");
  $(cleanprint_include_sel).addClass("cleanprint-include");

  $(".oer-chart-loading").first().after(
    "<p class='lace-cleanprint-diagram-warn cleanprint-include'>[ Diagrams and maps may not print or export well. Sorry! ]</p>");

  $cleanprint_bn_wrap = $(".lace-cleanprint-buttons");  //$("a[ onclick ^= WpCpCleanPrint ]").parent();
  $cleanprint_bn_wrap.find("a[onclick]").attr("role", "button");

  $("a[ onclick ^= WpCpCleanPrintPrint ]").attr("title",
    "Print/export page to rich-text, PDF & other formats");


    /* CleanPrint - keyboard accessibility hacks (incomplete!) (uses WAI-ARIA)
    */
    $cleanprint_bn_wrap.find("a").on("click", function (ev) {

      var $cleanprint_return = $(".lace-cleanprint-buttons a").first();

      setTimeout(function () {

        // 1. Make the 'close' pseudo-button into a functional button.
        $("#cpf-closeButton").attr({
          role: "button",
          tabindex: 0,
          title: "Close CleanPrint",
          "aria-label": "Close CleanPrint"
        })
        // 2. Start keyboard focus in a sensible place.
        .focus()
        // 3. Return keyboard focus when the user closes the CleanPrint dialog (by any means...)
        .on("click cleanprint_close", function () {
          $cleanprint_return.focus();
        });

        // 4. Let users know this is a modal dialog (needs more work).
        $("#cpf-root").attr({
          role: "dialog",
          "aria-label": "CleanPrint"
        });

        /* 5. Todo: prevent keyboard navigation outside the modal dialog, while its open.
           6. Todo: make all the pseudo-buttons inside the CleanPrint <iframe> into functional buttons.
           7. Todo: visual indication of keyboard focus in dialog - CSS - *:focus { outline: 1px dotted gray; }

        ... Test and iterate ..! */

      }, 4000);

    });

  }


  function when_call(when_true_FN, callback_FN, interval) {
    var int_id = setInterval(function () {
      if (when_true_FN()) {
        clearInterval(int_id);
        callback_FN();
      }
    }, interval || 200); // Milliseconds.
  }

  C && console.log('lace-javascript.js');
});

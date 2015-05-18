/*!
  Various Javascript fixes/hacks for LACE.
*/

jQuery(function ($) {

  var vis_table_row_sel = ".hypothesis #country-vis-table tbody tr";


  /* Fix for blank country-cell in [hypothesis_geosummary] table [Bug: #6]
  */
  when_call(function () {
    return $(vis_table_row_sel).length;
  },
  function () {
    var $vis_table_row = $(vis_table_row_sel);

    console.log("Vis table:", $vis_table_row);

    $vis_table_row.each(function () {

      var $row = $(this)
        , $cell_1 = $row.find("td").first();

      if ("" === $cell_1.text()) {
        $cell_1.html("<i>No location given</i>");

        $row().addClass("no-location-row").attr("title", "No location given");
      }

      console.log("Row:", $row, $cell_1);
    });

  });


  function when_call(when_true_FN, callback_FN, interval) {
    var int_id = setInterval(function () {
      if (when_true_FN()) {
        clearInterval(int_id);
        callback_FN();
      }
    }, interval || 200); // Milliseconds.
  }

  console.log('lace-javascript.js');
});
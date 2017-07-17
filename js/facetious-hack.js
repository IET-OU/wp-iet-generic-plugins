/*!
  JuxtaLearn - Facetious hack(s).
  Re-order the search form, promoting the "post-type" and subject fields.
*/

window.jQuery(function ($) {
  'use strict';

  var $form = $('.widget .facetious_form');
  var subject = 'facetious_juxtalearn_hub_subject';
  var selSubject = '.' + subject;
  var postType = '.facetious_post_type';
  var hack = '.hack';

  $(postType, $form).addClass('original').hide();
  $(selSubject, $form).addClass('original').hide();

  $('.facetious_search', $form).after("<p class='facetious_post_type hack'>");
  $(postType + hack).html($(postType + '.original').html());

  // Put 'subject' after post-type?
  $(postType + hack, $form).after("<p class='" + subject + " hack'>");
  $(selSubject + hack).html($(selSubject + '.original').html());

  // Remove the duplicate field "name".
  $(postType + '.original').empty();
  $(selSubject + '.original').empty();

  // [LACE]+
  $('#facetious_filter_evidence_hub_polarity option[ value = "" ]').text('Any Polarity');

  $form.find('.facetious_submit')
    .empty()
    .append('<button type="submit" class="facetious_submit_button">Search</button>');

  // Inject author field.
  $form
    .find('.facetious_submit')
    .before('<p class="facetious_author hack"><label for="f_an">Author</label>' +
    ' <input name="author_name" class="facetious_filter" id="f_an" placeholder="user-name" title="Example: \'rebeccaferguson\'"></p>');

  /* Accessibility [a11y][Bug: #17]
  */
  $form
    .attr({ role: 'search', 'aria-label': 'Filter evidence and projects' })
    .find('.facetious_input_search')
    .attr({ type: 'search' });

  // .
});

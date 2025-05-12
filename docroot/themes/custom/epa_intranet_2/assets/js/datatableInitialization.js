/**
 * @file
 */
(($, Drupal) => {

  // All the JavaScript for this file.
  Drupal.behaviors.initializeDatatables = {
    attach(context, settings) {
      $('.datatable', context).addClass('usa-table').DataTable();
    }
  };
})(jQuery, Drupal);
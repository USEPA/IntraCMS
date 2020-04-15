/**
 * @file
 */
(($, Drupal) => {

  // All the JavaScript for this file.
  Drupal.behaviors.initializeDatatables = {
    attach(context, settings) {
      $('.datatable', context).once('init').DataTable();
    }
  };
})(jQuery, Drupal);
(function (Drupal, $) {
  Drupal.behaviors.hierarchy_change = {
    attach: function (context, settings) {
      $(document).on('change', '.cshs-custom-select:last', function (event) {
        var selected_id = $('.cshs-custom-select').val();
        // lookup corresponding drupal term and populate with metadata
        $.get('/taxonomy_depth_lookup_standard/' + selected_id, function (data) {
          if (data.owner && data.size) {
            $('.all-windows-shares-metadata').show();
            $('.cshs-custom-select-additions').text(data.owner + ' - ' + data.size + '_gb');
          } else if (data.owner) {
            $('.all-windows-shares-metadata').show();
            $('.cshs-custom-select-additions').text(data.owner);
          } else if (data.size) {
            $('.all-windows-shares-metadata').show();
            $('.cshs-custom-select-additions').text(data.size + '_gb');
          } else {
            $('.all-windows-shares-metadata').hide();
          }
        });
      })
    }
  };

})(Drupal, jQuery);

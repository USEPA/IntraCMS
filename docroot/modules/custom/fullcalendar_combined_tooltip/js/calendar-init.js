(function ($, Drupal) {
  Drupal.behaviors.fullcalendarTooltip = {
    attach: function (context, settings) {
      console.log('✅ fullcalendarTooltip behavior attached');

      $.getJSON('/calendar-feed', function (data) {
        const descMap = {};

        data.forEach(function (event) {
          descMap[event.url] = event.extendedProps?.description || 'No description';
        });

        const waitForEvents = setInterval(function () {
          const $events = $('.fc-event:not(.fc-tooltip-processed)', context);

          if ($events.length) {
            clearInterval(waitForEvents);
            console.log(`🟢 Found ${$events.length} event(s) — adding tooltips`);

            $events.each(function () {
              const $event = $(this);
              const href = $event.attr('href');
              const description = descMap[href] || 'No description';

              $event.addClass('fc-tooltip-processed');
              //$event.css('border', '1px dashed red');

              if (typeof tippy === 'function') {
                tippy(this, {
                  content: description,
                  allowHTML: true,
                  arrow: true,
                  placement: 'top',
                  theme: 'light-border',
                });
                console.log('✨ Tooltip attached for:', href);
              }
            });
          }
        }, 300); // Check every 300ms until events exist
      }).fail(function () {
        console.error('❌ Failed to load /calendar-feed');
      });
    }
  };
})(jQuery, Drupal);
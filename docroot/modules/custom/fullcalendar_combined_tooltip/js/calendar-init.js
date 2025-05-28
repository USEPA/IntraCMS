(function ($, Drupal) {
  Drupal.behaviors.fullcalendarTooltip = {
    attach: function (context, settings) {
      console.log('✅ fullcalendarTooltip behavior attached');

      function attachTooltipsFromFeed() {
        $.getJSON('/calendar-feed', function (data) {
          const descMap = {};

          data.forEach(function (event) {
            descMap[event.url] = event.extendedProps?.description || 'No description';
          });

          $('.fc-event', context).each(function () {
            const $event = $(this);

            if (!$event.hasClass('fc-tooltip-processed')) {
              $event.addClass('fc-tooltip-processed');

              const href = $event.attr('href');
              const description = descMap[href] || 'No description';

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
            }
          });
        }).fail(function () {
          console.error('❌ Failed to load /calendar-feed');
        });
      }

      // Run once on initial page load
      attachTooltipsFromFeed();

      // Watch for DOM changes on calendar container
      const calendarContainer = document.querySelector('.fc'); // Fix here

      if (calendarContainer) {
        const observer = new MutationObserver(function (mutations) {
          for (const mutation of mutations) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
              setTimeout(attachTooltipsFromFeed, 200);
            }
          }
        });

        observer.observe(calendarContainer, {
          childList: true,
          subtree: true,
        });

        console.log('👀 Watching .fc for DOM changes');
      } else {
        console.warn('⚠️ .fc calendar container not found');
      }
    }
  };
})(jQuery, Drupal);
(function ($, Drupal) {
  Drupal.behaviors.fullcalendarTooltip = {
    attach: function (context, settings) {
      console.log('✅ fullcalendarTooltip behavior attached');

      // Load the description data from JSON feed
      function attachTooltipsAndFixTime() {
        $.getJSON('/calendar-feed', function (data) {
          const descMap = {};

          data.forEach(function (event) {
            descMap[event.url] = event.extendedProps?.description || 'No description';
          });

          // Attach tooltips and fix time for all visible events
          $('.fc-event', context).each(function () {
            const $event = $(this);

            // Tooltip setup
            if (!$event.hasClass('fc-tooltip-processed')) {
              $event.addClass('fc-tooltip-processed');

              const href = $event.attr('href');
              const description = descMap[href] || 'No description';

              if (typeof tippy === 'function') {
                tippy(this, {
                  content: description,
                  allowHTML: true,
                  arrow: true,
                  theme: 'light-border',
                  placement: 'auto',
                  maxWidth: 500,
                  interactive: true,
                  appendTo: document.body,
                  duration: [150, 100],
                  animation: 'scale',
                  popperOptions: {
                    modifiers: [
                      {
                        name: 'flip',
                        options: {
                          fallbackPlacements: ['bottom', 'top', 'right', 'left'],
                          padding: 10,
                        },
                      },
                      {
                        name: 'preventOverflow',
                        options: {
                          boundary: 'viewport',
                          padding: 10,
                        },
                      },
                      {
                        name: 'offset',
                        options: {
                          offset: [0, 10],
                        },
                      },
                    ],
                  },
                });
                console.log('✨ Tooltip attached for:', href);
              }
            }

            // Fix time formatting (e.g., 8a → 8:00 am)
            const $time = $event.find('.fc-event-time').not('.fc-time-fixed');
            $time.each(function () {
              const $el = $(this);
              const original = $el.text().trim();
              const match = original.match(/^(\d{1,2})(a|p)$/i);
              if (match) {
                const hour = parseInt(match[1]);
                const suffix = match[2].toLowerCase() === 'a' ? 'am' : 'pm';
                const formatted = `${hour}:00 ${suffix}`;
                console.log(`⏰ Converting "${original}" → "${formatted}"`);
                $el.text(formatted);
                $el.addClass('fc-time-fixed');
              }
            });
          });
        }).fail(function () {
          console.error('❌ Failed to load /calendar-feed');
        });
      }

      // Run once on initial page load
      attachTooltipsAndFixTime();

      // Watch for DOM changes when navigating calendar months
      const calendarContainer = document.querySelector('.fc');

      if (calendarContainer) {
        const observer = new MutationObserver(function (mutations) {
          for (const mutation of mutations) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
              setTimeout(attachTooltipsAndFixTime, 200);
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
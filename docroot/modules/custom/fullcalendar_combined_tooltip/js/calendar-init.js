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

            // ✅ TOOLTIP
            if (!$event.hasClass('fc-tooltip-processed')) {
              $event.addClass('fc-tooltip-processed');
              const href = $event.attr('href');
              const description = descMap[href] || 'No description';

              if (typeof tippy === 'function') {
                tippy(this, {
                  content: description,
                  allowHTML: true,
                  theme: 'light-border',
                  placement: 'auto',
                  maxWidth: 500,
                  interactive: true,
                  appendTo: document.body,
                });
              }
            }

            // ✅ TIME FIXING
            $event.find('.fc-event-time').each(function () {
              const $el = $(this);
              let raw = $el.text().trim();

              // Remove all existing formatting artifacts like "2:00 pm2p"
              raw = raw.replace(/(\d{1,2}:\d{2} (am|pm))?(\d{1,2})(a|p)/i, '$3$4').trim();

              let formatted = raw;

              if (/^\d{1,2}a$/i.test(raw)) {
                const hour = raw.replace(/a/i, '');
                formatted = `${hour}:00 am`;
              } else if (/^\d{1,2}p$/i.test(raw)) {
                const hour = raw.replace(/p/i, '');
                formatted = `${hour}:00 pm`;
              } else if (/^\d{1,2}:\d{2}(a|p)m?$/i.test(raw)) {
                formatted = raw.replace(/^(\d{1,2}):(\d{2})(a|p)m?$/i, '$1:$2 $3m');
              }

              console.log(`⏰ Final clean: "${raw}" → "${formatted}"`);
              $el.text(formatted); // fully overwrite
            });
          });
        });
      }

      // Run on load
      attachTooltipsAndFixTime();

      // Watch for calendar month/week/day changes
      const calendarContainer = document.querySelector('.fc');
      if (calendarContainer) {
        const observer = new MutationObserver(function (mutations) {
          for (const mutation of mutations) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
              setTimeout(attachTooltipsAndFixTime, 200);
              break;
            }
          }
        });

        observer.observe(calendarContainer, {
          childList: true,
          subtree: true,
        });

        console.log('👀 Watching .fc for DOM changes');
      } 
    }
  };
})(jQuery, Drupal);
(function ($, Drupal) {
  Drupal.behaviors.fullcalendarTooltip = {
    attach: function (context, settings) {
      //console.log('✅ fullcalendarTooltip behavior attached');

      // Load the description and color data from JSON feed
      function attachTooltipsAndFixTimeAndColor() {
        $.getJSON('/calendar-feed', function (data) {
          const descMap = {};
          const colorMap = {};
          const textColorMap = {};

          data.forEach(function (event) {
            descMap[event.url] = event.extendedProps?.description || 'No description';
            if (event.backgroundColor) {
              colorMap[event.url] = event.backgroundColor;
            }
            if (event.textColor) {
              textColorMap[event.url] = event.textColor;
            }
          });

          // Attach tooltip, fix time, and apply background color
          $('.fc-event', context).each(function () {
            const $event = $(this);
            const href = $event.attr('href');

            // ✅ TOOLTIP
            if (!$event.hasClass('fc-tooltip-processed')) {
              $event.addClass('fc-tooltip-processed');
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

              // Clean up extra formatting
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

              //console.log(`⏰ Final clean: "${raw}" → "${formatted}"`);
              $el.text(formatted);
            });

            // ✅ APPLY COLORS
            if (colorMap[href]) {
              $event.css('background-color', colorMap[href]);
              $event.css('border-color', colorMap[href]);
            }
            if (textColorMap[href]) {
              $event.css('color', textColorMap[href]);
            }
          });
        });
      }

      // Run on initial load
      attachTooltipsAndFixTimeAndColor();

      // Watch for calendar updates (month/week/day changes)
      const calendarContainer = document.querySelector('.fc');
      if (calendarContainer) {
        const observer = new MutationObserver(function (mutations) {
          for (const mutation of mutations) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
              setTimeout(attachTooltipsAndFixTimeAndColor, 200);
              break;
            }
          }
        });

        observer.observe(calendarContainer, {
          childList: true,
          subtree: true,
        });

        //console.log('👀 Watching .fc for DOM changes');
      }
    }
  };
})(jQuery, Drupal);
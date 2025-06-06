// ✅ Add this at the very top -Testing fix-time-format file loading or not
//console.log('🎯 fix-time-format.js is loading... ');

(function ($, Drupal) {
  Drupal.behaviors.fixTimeFormat = {
    attach: function (context, settings) {
      console.log('🎯 fix-time-format.js is running...');

      // Delay to wait for FullCalendar to render
      let retryCount = 0;
      const interval = setInterval(() => {
        const $times = $('.fc-event-time', context).not('.fc-time-fixed');
        if ($times.length > 0 || retryCount >= 10) {
          clearInterval(interval);
          $times.each(function () {
            const $el = $(this);
            const original = $el.text().trim();
            console.log('🔍 Found event time:', original);
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
        } else {
          retryCount++;
        }
      }, 500);
    }
  };
})(jQuery, Drupal);


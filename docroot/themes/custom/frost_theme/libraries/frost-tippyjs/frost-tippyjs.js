/**
 * @file
 * Theme code for using tippyjs
 */

// Initialize tippy generically.
tippy('[data-tippy-content]');

var tippyInitializationFunction = function(initType) {
  // Add tips for hotspots.
  tippy('[data-tippy-interactive]', {
    content(reference) {
      const id = reference.getAttribute('aria-controls');
      const template = document.getElementById(id);
      return template.innerHTML;
    },
    duration: [800, 100],
    placement: 'bottom',
    trigger: 'mouseenter'
  });
};
utilityInitializer('entity-bundle-hotspot', 'tippyInitializationFunction');

/* Use event delegation for any dynamically-added events. */
document.addEventListener('click', function (event) {
  if (event.target !== document
    && event.target.closest('.hotspot-anchor')
  ) {
    event.preventDefault();
  }
}, false);

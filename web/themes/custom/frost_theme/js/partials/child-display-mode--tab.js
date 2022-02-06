/**
 * @file
 * Set up stripe collections as tabs.
 */

var childTabsInitializationFunction = function (initType) {
  const thisId = this.getAttribute('id');

  // Vanilla equivalent of $().wrapInner();
  let wrapper = document.createElement('div');
  this.appendChild(wrapper).classList.add('tabs--content');
  while (this.firstChild !== wrapper) {
    wrapper.appendChild(this.firstChild);
  }

  // Build a tabs list and then prepend it to the wrapper.
  let tabsList = '<ul role="tablist" class="tabs">';
  const thisDetails = this.querySelectorAll('details');
  Array.prototype.forEach.call(thisDetails, (elem, i) => {
    // Open the first tab by default.
    if (!i) {
      elem.setAttribute('open', true);
    }

    // The first tab should have a class and have aria indication.
    let classString = (!i) ? ' class="is-active" aria-selected="true"' : '';
    let hashString = elem.getAttribute('id');
    let tabTitle = elem.querySelector('summary').textContent;

    // Ugly way to construct list items.
    tabsList += '<li role="presentation"><a id="' + thisId + '-tab-' +
      i + '" role="tab" href="#' + hashString + '"' + classString +
      '>' + tabTitle + '</a>';

    // Throw a little more aria markup on the tab content.
    elem.setAttribute('aria-labelledby', thisId + '-tab-' + i);
  });

  tabsList += '</ul>';
  this.insertAdjacentHTML('afterbegin', tabsList);
};
utilityInitializer('child-display-mode--tab', 'childTabsInitializationFunction');

/* Use event delegation for any dynamically-added events. */
document.addEventListener('click', function (event) {
  if (event.target !== document
    && event.target.closest('.tabs a')
  ) {
    if (!event.target.closest('#block-frost-theme-local-tasks')) {
      event.preventDefault();
    }

    let bubbled = event.target.closest('.tabs a');
    const idSelector = bubbled.getAttribute('href');

    // Unset this and all sibling link classes and aria attributes.
    const allTabs = bubbled.closest('.tabs').querySelectorAll('a');
    Array.prototype.forEach.call(allTabs, (elem, i) => {
      elem.classList.remove('is-active');
      elem.removeAttribute('aria-selected');
    });

    // Set this link class to 'is-active'.
    bubbled.setAttribute('aria-selected', true);
    bubbled.classList.add('is-active');

    // We'll need to manipulate all the child tabs, so here's the parent
    // element.
    const parentElem = bubbled.closest('.child-display-mode--tab');

    // Close all the details below this tabs list.
    const allDetails = parentElem.querySelectorAll('details');
    Array.prototype.forEach.call(allDetails, (elem, i) => {
      elem.removeAttribute('open');
    });

    // Find the element with this link's hash. Traverse up to its <details>
    // parent and open it.
    const clickedDetails = parentElem.querySelector(idSelector).setAttribute('open', true);
  }
}, false);

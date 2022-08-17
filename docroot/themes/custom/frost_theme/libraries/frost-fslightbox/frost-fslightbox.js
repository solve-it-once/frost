/**
 * @file
 * Initialize and control lightboxes for the media listing component.
 */

var fslightboxInitializationFunction = function(initType) {
  const thisId = this.getAttribute('id');

  // Iterate over children to add lightbox data attributes.
  const thisLinks = this.querySelectorAll('figure > a');
  Array.prototype.forEach.call(thisLinks, (elem, i) => {
    elem.setAttribute('data-fslightbox', thisId + '-link-' + i);
  });

  // Re-initialize the library, if present.
  if (typeof refreshFsLightbox === 'function') {
    refreshFsLightbox();
  }
};
utilityInitializer('js--frost-fslightbox', 'fslightboxInitializationFunction');

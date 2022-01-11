/**
 * @file
 * Class the body with scroll state.
 */

var swapPictureSrc = function(elem) {
  const dataSrc = elem.querySelectorAll('[data-src],[data-srcset]');
  Array.prototype.forEach.call(dataSrc, (thisSrc) => {
    if (thisSrc.dataset.src) {
      thisSrc.src = thisSrc.dataset.src;
      delete thisSrc.dataset.src;
    }
    else {
      thisSrc.srcset = thisSrc.dataset.srcset;
      delete thisSrc.dataset.srcset;
    }
  });
  elem.classList.add('picture--lazy-load--loaded');
};

if ('IntersectionObserver' in window) {
  document.body.classList.add('js--animation');

  let animationObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.intersectionRatio >= 0.5) {
        if (entry.target.classList.contains('picture--lazy-load')) {
          swapPictureSrc(entry.target);
        }
        else if (entry.target.classList.contains('field-type--decimal')) {
          // @todo repair this to play nice with decimals.
          var thisPrecision = 0;
          if (entry.target.textContent.indexOf('.') !== -1) {
            thisPrecision = entry.target.textContent.split(".")[1].length;
          }
          var targetNum = Number.parseFloat(entry.target.textContent);

          entry.target.classList.add('js--animation--observed');
          var current = 0;
          if (thisPrecision >= 1) {
            current = 0 + Number.parseFloat(entry.target.textContent.split(".")[1]).toFixed(thisPrecision);
          }
          entry.target.textContent = current;

          var stepTime = Math.abs(Math.floor(2500 / targetNum));
          var timer = setInterval(function() {
            current++;
            entry.target.textContent = current;
            if (current >= targetNum) {
              clearInterval(timer);
            }
          }, stepTime);
        }
        else {
          entry.target.classList.add('js--animation--observed');

          // Support animate.css.
          if (entry.target.dataset.animation) {
            entry.target.classList.add('animated');
            entry.target.classList.add(entry.target.dataset.animation);
          }
        }

        animationObserver.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.5
  });

  var animationInitializationFunction = function(initType) {
    animationObserver.observe(this);
  };
  utilityInitializer('js--to-animate', 'animationInitializationFunction');
  utilityInitializer('field-type--decimal', 'animationInitializationFunction');
  /* Wait until blocking resources have loaded to bring in images. */
  window.addEventListener('load', (event) => {
    utilityInitializer('picture--lazy-load', 'animationInitializationFunction');
  });
}
else {
  const pictureLazyLoad = elem.querySelectorAll('.picture--lazy-load');
  Array.prototype.forEach.call(pictureLazyLoad, (thisPic) => {
    swapPictureSrc(thisPic);
  });
}

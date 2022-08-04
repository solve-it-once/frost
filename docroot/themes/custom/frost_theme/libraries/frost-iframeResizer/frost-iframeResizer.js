/**
 * @file
 * Theme code for using iframeResizer
 */

var iframeResizerInitializationFunction = function(initType) {
  if (typeof iFrameResize === 'function') {
    iFrameResize(null, '.js--iframe-resize iframe');
  }
};
utilityInitializer('js--iframe-resize', 'iframeResizerInitializationFunction');

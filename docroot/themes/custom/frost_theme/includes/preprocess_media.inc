<?php

/**
 * @file
 * Preprocess.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess_entity().
 */
function frost_theme_preprocess_media(&$vars) {
  $entity = $vars['media'];
  $type = $entity->getEntityTypeId();
  $bundle = $entity->bundle();
  $view_mode = $vars['elements']['#view_mode'];

  $base_classes = [
    Html::cleanCssIdentifier('media'),
    Html::cleanCssIdentifier('entity-type-' . $type),
    Html::cleanCssIdentifier('entity-bundle-' . $bundle),
    Html::cleanCssIdentifier('media--' . $bundle . '--' . $view_mode),
    Html::cleanCssIdentifier('entity-' . $bundle . '-' . $entity->id()),
  ];

  // Either add to or create the base classes, whether they're used or not.
  if (isset($vars['attributes']['class'])) {
    $vars['attributes']['class'] += $base_classes;
  }
  else {
    // Check if the attributes have been made.
    if (!isset($vars['attributes'])) {
      $vars['attributes'] = new Attribute([
        'class' => [],
      ]);
    }

    $vars['attributes']['class'] = $base_classes;
  }

  $where = &$vars['attributes']['class'];
  _frost_theme_entity_style_classes($vars, $entity, $where);
}

/**
 * Implements hook_preprocess_file_video().
 */
function frost_theme_preprocess_file_video(&$vars) {
  // If a video file is set to autoplay, also set it to mute.
  $autoplay = FALSE;
  foreach ($vars['attributes'] as $key => $value) {
    if ($key === 'autoplay') {
      $autoplay = TRUE;
      break;
    }
  }

  if ($autoplay) {
    $vars['attributes']['muted'] = 'muted';
  }
}

/**
 * Implements hook_preprocess_element().
 */
function frost_theme_preprocess_video_embed_iframe(&$vars) {
  $vars['attributes']->setAttribute('enablejsapi', 'true');
}

/**
 * Implements hook_preprocess_template().
 */
function frost_theme_preprocess_image(&$vars) {
  // Add lazy loading to all images.
  // @todo If can be done with performance, don't lazy load above "the fold".
  $vars['attributes']['loading'] = 'lazy';

  // Add image dimensions for browser hinting if not present by now.
  if (empty($vars['width']) || empty($vars['height'])) {
    $path = ltrim($vars['uri'], '/');
    $image_size = _frost_theme_getimagesize($path);
    if (isset($image_size['width'])) {
      $vars['height'] = $image_size['height'];
      $vars['attributes']['height'] = $image_size['height'];
      $vars['width'] = $image_size['width'];
      $vars['attributes']['width'] = $image_size['width'];
    }
  }
}

/**
 * Helper function to get image size for SVG as well as bitmapped images.
 *
 * @param string $path
 *   The image path, relative to the docroot.
 *
 * @return array|false
 *   An array with height and width values, or false if there's trouble.
 */
function _frost_theme_getimagesize($path) {
  $return = FALSE;
  $ext = mb_substr($path, mb_strripos($path, '.') + 1);

  if ($ext === 'svg') {
    // Use XML structure to determine plausible dimensions.
    if (file_exists($path)) {
      $svg = simplexml_load_file($path);
      $attributes = $svg->attributes();
      if (!empty($attributes) && property_exists($attributes, 'height')) {
        $return = [
          'width' => (string) $attributes->width,
          'height' => (string) $attributes->height,
        ];
      }
    }
  }
  else {
    if (file_exists($path)) {
      $image_size = getimagesize($path);
      if (isset($image_size) && count($image_size) >= 2) {
        $return = [
          'width' => $image_size[0],
          'height' => $image_size[1],
        ];
      }
    }
  }

  return $return;
}

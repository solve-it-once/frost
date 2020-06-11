<?php

/**
 * @file
 * Preprocess
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess_entity().
 * @param $vars
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
  $vars['attributes']['loading'] = 'lazy';
}

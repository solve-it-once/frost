<?php

/**
 * @file
 * Preprocess
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess_field().
 */
function frost_theme_preprocess_field(&$vars) {
  $vars['wrapped'] = TRUE;
  $vars['html_tag'] = 'div';
  $vars['classes'] = [
    'field',
    Html::cleanCssIdentifier('field-name--' . $vars['field_name']),
    Html::cleanCssIdentifier('field-type--' . $vars['field_type']),
    Html::cleanCssIdentifier('field-label--' . $vars['label_display']),
  ];

  // Class the field formatter.
  if (isset($vars['element']['#formatter'])) {
    $vars['classes'][] = Html::cleanCssIdentifier('field-formatter--' . $vars['element']['#formatter']);
  }

  // Title tag classes.
  $vars['title_classes'] = [
    'field-label',
  ];
  if (isset($vars['label_display']) && $vars['label_display'] === 'visually_hidden') {
    $vars['title_classes'][] = 'visually-hidden';
  }

  // Whether the field should be wrapped or not.
  if (
    in_array($vars['field_type'], [
      'entity_reference',
      'entity_reference_revisions',
      'image',
      'link',
    ])
    || in_array($vars['field_name'], ['field_longtext', 'field_text'])
  ) {
    $vars['wrapped'] = FALSE;

    // Unless the formatter is just the label, in which case we wrap it.
    if ($vars['element']['#formatter'] === 'entity_reference_label') {
      $vars['wrapped'] = TRUE;
    }
  }

  // Tweak the wrapper HTML tag.
  if ($vars['entity_type'] === 'paragraph') {
    switch ($vars['element']['#bundle']) {
      case 'accordion':
        if ($vars['field_name'] === 'field_title') {
          $vars['html_tag'] = 'summary';
        }

        break;

      case 'card':
        if ($vars['field_name'] === 'field_title') {
          $vars['html_tag'] = 'h2';
        }

        break;

      case 'media':
        if ($vars['field_name'] === 'field_text') {
          $vars['html_tag'] = 'figcaption';
          $vars['wrapped'] = TRUE;
        }

        break;
    }
  }

  // By default, set any entity references showing teasers on nodes, etc.
  // to be a carousel.
  if (in_array($vars['entity_type'], ['node', 'taxonomy_term', 'commerce_product'])) {
    if ($vars['element']['#formatter'] === 'entity_reference_entity_view') {
      if (isset($vars['element'][0]['#view_mode'])
        && $vars['element'][0]['#view_mode'] === 'teaser'
        && !isset($vars['element'][0]['#media'])
      ) {
        $vars['layout_carousel'] = TRUE;
      }
    }
  }

  // Fields by field machine name.
  switch ($vars['field_name']) {
    case 'field_components_cta':
      $vars['wrapped'] = TRUE;
      $vars['classes'][] = Html::cleanCssIdentifier('display--flex');
      $vars['classes'][] = Html::cleanCssIdentifier('flex-direction--column');
      $vars['classes'][] = Html::cleanCssIdentifier('justify-content--center');
      $vars['classes'][] = Html::cleanCssIdentifier('child-links--as-buttons');

      break;

    case 'field_image':
      $vars['wrapped'] = TRUE;
      $vars['html_tag'] = 'figure';

      break;

    case 'field_name':
      $vars['html_tag'] = 'h3';
      $vars['classes'][] = Html::cleanCssIdentifier('color--second');
      $vars['classes'][] = Html::cleanCssIdentifier('heading--h4');

      break;

    case 'title':
      $vars['html_tag'] = 'h2';

      if (isset($vars["element"]["#view_mode"]) && $vars["element"]["#view_mode"] === 'hero') {
        $vars['html_tag'] = 'h1';
      }

      break;
  }
}

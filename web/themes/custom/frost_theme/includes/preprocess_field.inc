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
          $vars['html_tag'] = 'h3';
          $vars['classes'][] = Html::cleanCssIdentifier('margin-vertical--1');
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

  // For text fields, class the result depending on how much text is there.
  if (isset($vars['element'][0]['#text'])) {
    $vars['classes'][] = Html::cleanCssIdentifier('has--text');

    $strlen = mb_strlen(strip_tags($vars['element'][0]['#text']));
    if ($strlen >= 1000) {
      $vars['classes'][] = Html::cleanCssIdentifier('text-length--extra-long');
    }
    else if ($strlen >= 600) {
      $vars['classes'][] = Html::cleanCssIdentifier('text-length--long');
    }
    else if ($strlen >= 280) {
      $vars['classes'][] = Html::cleanCssIdentifier('text-length--medium');
    }
    else if ($strlen >= 140) {
      $vars['classes'][] = Html::cleanCssIdentifier('text-length--short');
    }
    else {
      $vars['classes'][] = Html::cleanCssIdentifier('text-length--extra-short');
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
    case 'body':
      if (isset($vars['element']['#view_mode']) && $vars['element']['#view_mode'] === 'hero') {
        $vars['classes'][] = Html::cleanCssIdentifier('font-size--1-125em');
        $vars['classes'][] = Html::cleanCssIdentifier('margin-vertical--2');
      }

      break;

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

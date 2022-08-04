<?php

/**
 * @file
 * Preprocess.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess_views_view().
 */
function frost_theme_preprocess_views_view(&$vars) {
  $vars['classes'] = [
    'view',
    Html::cleanCssIdentifier('view-' . $vars['id']),
    Html::cleanCssIdentifier('view-id-' . $vars['id']),
    Html::cleanCssIdentifier('view-display-id-' . $vars['display_id']),
  ];

  if (isset($vars['dom_id']) && mb_strlen($vars['dom_id'])) {
    $vars['classes'][] = Html::cleanCssIdentifier('js-view-dom-id-' . $vars['dom_id']);
  }

  // Set separate attribute arrays for the content, header, and footer.
  $vars['header_attributes'] = new Attribute([
    'class' => [
      'view-header',
    ],
  ]);
  $vars['filters_attributes'] = new Attribute([
    'class' => [
      'view-filters',
    ],
  ]);
  $vars['attachment_before_attributes'] = new Attribute([
    'class' => [
      'attachment',
      'attachment-before',
    ],
  ]);
  $vars['content_attributes']['class'][] = 'view-content';
  $vars['empty_attributes'] = new Attribute([
    'class' => [
      'view-empty',
    ],
  ]);
  $vars['attachment_after_attributes'] = new Attribute([
    'class' => [
      'attachment',
      'attachment-after',
    ],
  ]);
  $vars['footer_attributes'] = new Attribute([
    'class' => [
      'view-footer',
    ],
  ]);
  $vars['feed_icons_attributes'] = new Attribute([
    'class' => [
      'feed-icons',
    ],
  ]);

  // Any display's machine name that starts with 'horizontal' automatically gets
  // classed to show as horizontal cards.
  if (mb_substr($vars['display_id'], 0, 10) === 'horizontal') {
    $vars['content_attributes']['class'][] = 'flex-wrap--wrap';
    $vars['content_attributes']['class'][] = 'layout--flex-row';
    $vars['content_attributes']['class'][] = 'layout--horizontal';
  }

  // Make displays, mostly pages, look like stripes, but only their content.
  if ($vars['id'] === 'taxonomy_term' && $vars['display_id'] === 'horizontal_1') {
    $vars['content_attributes']['class'][] = 'appear-as-stripe';
  }
}

/**
 * Implements hook_preprocess_element().
 */
function frost_theme_preprocess_views_view_unformatted(&$vars) {
  $view = $vars['view'];
  $display = $view->getDisplay();
  $rowPlugin = $display->getPlugin('row');
  $pluginId = $rowPlugin->getPluginId();
  $vars['attributes']['class'][] = 'views-row';

  // Have a boolean for whether it's fielded or not, for templates.
  $vars['fielded'] = FALSE;
  if (isset($pluginId) && $pluginId === 'fields') {
    $vars['fielded'] = TRUE;

    // If fields, but the only field is a rendered entity, remove the wrapper.
    // Note: Be sure to remove wrappers via the views admin UI.
    if ((isset($view->field) && count($view->field) === 1 && array_key_exists('rendered_entity', $view->field))) {
      $vars['fielded'] = FALSE;
    }
  }
}

/**
 * Implements hook_preprocess_element().
 */
function frost_theme_preprocess_views_exposed_form(&$vars) {
  // Set forms to be toggleable, then set the conditions when it is not.
  $vars['toggleable'] = FALSE;
  if (!in_array($vars['form']['#id'], ['views-exposed-form-search-block-1'])) {
    $vars['toggleable'] = TRUE;
    $vars['attributes']['class'][] = 'filter-toggle-target';
    $vars['attributes']['class'][] = 'filter-toggle-target--closed';
    $vars['attributes']['class'][] = 'display--flex';
    $vars['attributes']['class'][] = 'layout--flex-row';
    $vars['attributes']['class'][] = 'flex-direction--row';
    $vars['attributes']['class'][] = 'align-items--center';
    $vars['attributes']['class'][] = 'justify-content--center';
  }
  else {
    $vars['attributes']['class'][] = 'form--inline';
    $vars['attributes']['class'][] = 'views-exposed-form--inline';
    $vars['attributes']['class'][] = 'display--flex';
    $vars['attributes']['class'][] = 'layout--flex-row';
    $vars['attributes']['class'][] = 'flex-direction--row';
    $vars['attributes']['class'][] = 'align-items--center';
    $vars['attributes']['class'][] = 'justify-content--center';
  }

  if (isset($vars['attributes']['data-drupal-selector'])) {
    $vars['attributes']['class'][] = $vars['attributes']['data-drupal-selector'];
  }

  // Don't wrap the search form so it plays nice in the header on IE.
  if (($vars['attributes']['data-drupal-selector'] !== 'views-exposed-form-search-page')
    && ($vars['attributes']['data-drupal-selector'] !== 'views-exposed-form-search-block-1')) {
    $vars['attributes']['class'][] = 'flex-wrap--wrap';
  }
}

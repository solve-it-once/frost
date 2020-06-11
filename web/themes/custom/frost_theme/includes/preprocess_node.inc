<?php

/**
 * @file
 * Preprocess
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess_node().
 */
function frost_theme_preprocess_node(&$vars) {
  $node = $vars['node'];

  $vars['classes'] = [
    Html::cleanCssIdentifier('node'),
    Html::cleanCssIdentifier('node-type--' . $node->bundle()),
    Html::cleanCssIdentifier('node-view-mode--' . $vars['view_mode']),
    Html::cleanCssIdentifier('view-mode--' . $vars['view_mode']),
    Html::cleanCssIdentifier('node--' . $node->bundle() . '-' . $vars['view_mode']),
  ];
  if ($node->isPromoted()) {
    $vars['classes'][] = Html::cleanCssIdentifier('node--promoted');
  }
  if ($node->isSticky()) {
    $vars['classes'][] = Html::cleanCssIdentifier('node--sticky');
  }
  if (!$node->isPublished()) {
    $vars['classes'][] = Html::cleanCssIdentifier('node--unpublished');
  }

  // Check if body (teaser text) is present, and class if so.
  if ($node->hasField('body')) {
    $values = $node->get('body')->getValue();
    if (!isset($values) || !count($values)) {
      $vars['classes'][] = Html::cleanCssIdentifier('lacks--body');
    }
    else {
      $vars['classes'][] = Html::cleanCssIdentifier('has--body');
    }
  }

  switch ($node->bundle()) {
    case 'article':
      // Content type specific code here.

      break;
  }

  // Add classes and jazz to the image column, if present.
  $vars['teaser_attributes'] = new Attribute([
    'class' => [
      Html::cleanCssIdentifier($vars['view_mode'] . '--image'),
      'flex--1',
      'text-align--center',
    ],
  ]);

  // Classes and other preprocess by view mode.
  switch ($vars['view_mode']) {
    case 'hero':
      $vars['classes'][] = Html::cleanCssIdentifier('display--flex');
      $vars['classes'][] = Html::cleanCssIdentifier('layout--flex-row');
      $vars['classes'][] = Html::cleanCssIdentifier('justify-content--center');
      $vars['classes'][] = Html::cleanCssIdentifier('appear-as-stripe');

      break;

    case 'small':
      $vars['classes'][] = Html::cleanCssIdentifier('js--child--link');
      $vars['classes'][] = Html::cleanCssIdentifier('flex--1');
      $vars['classes'][] = Html::cleanCssIdentifier('text-align--left');
      $vars['classes'][] = Html::cleanCssIdentifier('display--flex');
      $vars['classes'][] = Html::cleanCssIdentifier('flex-direction--column');
      $vars['classes'][] = Html::cleanCssIdentifier('justify-content--center');

      break;

    case 'teaser':
      $vars['classes'][] = Html::cleanCssIdentifier('js--child--link');
      $vars['classes'][] = Html::cleanCssIdentifier('flex--1');
      $vars['classes'][] = Html::cleanCssIdentifier('display--flex');
      $vars['classes'][] = Html::cleanCssIdentifier('layout--flex-row');
      $vars['classes'][] = Html::cleanCssIdentifier('text-align--center');
      $vars['classes'][] = Html::cleanCssIdentifier('flex-direction--column');
      $vars['classes'][] = Html::cleanCssIdentifier('justify-content--flex-start');
      $vars['classes'][] = Html::cleanCssIdentifier('padding--20px');

      break;
  }

  // Any field_style_... fields print as CSS classes.
  $where = &$vars['attributes']['class'];
  _frost_theme_entity_style_classes($vars, $node, $where);
}

<?php

/**
 * @file
 * Preprocess.
 */

use Drupal\Component\Utility\Html;

/**
 * Implements hook_preprocess_TEMPLATE().
 */
function frost_theme_preprocess_commerce_product(&$vars) {
  $entity = $vars['product_entity'];
  $view_mode = $vars['elements']['#view_mode'];

  $vars['classes'] = [
    Html::cleanCssIdentifier('product'),
    Html::cleanCssIdentifier('product-type--' . $entity->bundle()),
    Html::cleanCssIdentifier('product-view-mode--' . $view_mode),
    Html::cleanCssIdentifier('view-mode--' . $view_mode),
    Html::cleanCssIdentifier('product--' . $entity->bundle() . '-' . $view_mode),
  ];

  switch ($entity->bundle()) {
    case 'page':
      // Something by product type.
      break;
  }

  // Classes and other preprocess by view mode.
  switch ($view_mode) {
    case 'teaser':
      $vars['classes'][] = Html::cleanCssIdentifier('js--child--link');
      $vars['classes'][] = Html::cleanCssIdentifier('flex--1');
      $vars['classes'][] = Html::cleanCssIdentifier('display--flex');
      $vars['classes'][] = Html::cleanCssIdentifier('flex-direction--column');
      $vars['classes'][] = Html::cleanCssIdentifier('text-align--left');
      $vars['classes'][] = Html::cleanCssIdentifier('justify-content--flex-start');
      $vars['classes'][] = Html::cleanCssIdentifier('background-color--white');
      $vars['classes'][] = Html::cleanCssIdentifier('border--1px-solid');
      $vars['classes'][] = Html::cleanCssIdentifier('border-color--grey-light');
      $vars['classes'][] = Html::cleanCssIdentifier('border-radius--default');
      $vars['classes'][] = Html::cleanCssIdentifier('box-shadow--default');

      break;
  }

  // Any field_style_... fields print as CSS classes.
  $where = &$vars['attributes']['class'];
  _frost_theme_entity_style_classes($vars, $entity, $where);
}

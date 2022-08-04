<?php

/**
 * @file
 * Preprocess.
 */

use Drupal\Component\Utility\Html;

/**
 * Implements hook_preprocess_entity().
 */
function frost_theme_preprocess_user(&$vars) {
  $vars['html_tag'] = 'div';
  $entity = $vars['user'];
  $view_mode = $vars['elements']['#view_mode'];

  $vars['attributes']['class'] = [
    Html::cleanCssIdentifier('user'),
    Html::cleanCssIdentifier('view-mode--' . $view_mode),
    Html::cleanCssIdentifier('user-uid--' . $entity->id()),
  ];
  $vars['attributes']['id'] = Html::cleanCssIdentifier('user--' . $entity->id());

  switch ($view_mode) {
    case 'teaser':
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex--1');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('display--flex');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('layout--flex-row');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('text-align--center');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex-direction--column');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('justify-content--flex-start');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('padding--20px');

      break;
  }
}

<?php

/**
 * @file
 * Preprocess.
 */

use Drupal\Component\Utility\Html;

/**
 * Implements hook_preprocess_region().
 */
function frost_theme_preprocess_region(&$vars) {
  $vars['html_tag'] = 'div';
  $vars['wrapped'] = TRUE;
  $vars['inner'] = TRUE;
  $vars['classes'] = [
    Html::cleanCssIdentifier('region'),
    Html::cleanCssIdentifier('region--' . $vars['region']),
  ];
  $vars['content_attributes']['class'][] = Html::cleanCssIdentifier('inner');

  switch ($vars['region']) {
    case 'alerts':
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('background-color--white');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('display--flow-root');

      break;

    case 'footer_menu':
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('padding-horizontal--collapse');

      break;

    case 'header':
      $vars['html_tag'] = 'header';

      break;

    case 'nav':
      $vars['wrapped'] = FALSE;
      $vars['inner'] = FALSE;

      break;

    case 'sidebar_first':
    case 'sidebar_second':
      $vars['html_tag'] = 'aside';
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex--1');

      if ($vars['region'] === 'sidebar_first') {
        $vars['attributes']['class'][] = Html::cleanCssIdentifier('order--neg1');
      }

      break;

    case 'utility':
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('background-color--main-dark');
      $vars['content_attributes']['class'][] = Html::cleanCssIdentifier('display--flex');
      $vars['content_attributes']['class'][] = Html::cleanCssIdentifier('color--white');

      break;
  }
}

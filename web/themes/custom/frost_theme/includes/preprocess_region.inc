<?php

/**
 * @file
 * Preprocess
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess_region().
 */
function frost_theme_preprocess_region(&$vars) {
  $vars['html_tag'] = 'div';
  $vars['classes'] = [
    Html::cleanCssIdentifier('region'),
    Html::cleanCssIdentifier('region--' . $vars['region']),
  ];
  $vars['content_attributes']['class'][] = Html::cleanCssIdentifier('inner');

  switch ($vars['region']) {
    case 'header':
      $vars['html_tag'] = 'header';

      break;

    case 'nav':
      break;

    case 'sidebar_first':
    case 'sidebar_second':
      $vars['html_tag'] = 'aside';

      break;

    case 'utility':
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('background-color--main-dark');
      $vars['content_attributes']['class'][] = Html::cleanCssIdentifier('display--flex');
      $vars['content_attributes']['class'][] = Html::cleanCssIdentifier('color--white');

      break;
  }
}

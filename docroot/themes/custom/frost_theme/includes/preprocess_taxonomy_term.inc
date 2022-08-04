<?php

/**
 * @file
 * Preprocess.
 */

use Drupal\Component\Utility\Html;

/**
 * Implements hook_preprocess_taxonomy_term().
 */
function frost_theme_preprocess_taxonomy_term(&$vars) {
  $term = $vars['term'];
  $bundle = $term->bundle();

  $vars['classes'] = [
    Html::cleanCssIdentifier('taxonomy-term'),
    Html::cleanCssIdentifier('vocabulary--' . $bundle),
    Html::cleanCssIdentifier('taxonomy-term--view-mode--' . $vars['view_mode']),
    Html::cleanCssIdentifier('view-mode--' . $vars['view_mode']),
  ];

  $vars['attributes']['id'] = Html::cleanCssIdentifier('taxonomy-term-' . $term->id());

  // Classes and other preprocess by view mode.
  switch ($vars['view_mode']) {
    case 'hero':
      $vars['classes'][] = Html::cleanCssIdentifier('display--flex');
      $vars['classes'][] = Html::cleanCssIdentifier('layout--flex-row');
      $vars['classes'][] = Html::cleanCssIdentifier('justify-content--center');
      $vars['classes'][] = Html::cleanCssIdentifier('appear-as-stripe');

      break;

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

  switch ($bundle) {
    case 'tags':
      // Something by vocabulary.
      break;
  }

  // Any field_style_... fields print as CSS classes.
  $where = &$vars['classes'];
  _frost_theme_entity_style_classes($vars, $term, $where);
}

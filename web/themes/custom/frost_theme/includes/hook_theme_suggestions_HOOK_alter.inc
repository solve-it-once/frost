<?php

/**
 * @file
 * Suggest theme templates based on available vars.
 */

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function frost_theme_theme_suggestions_taxonomy_term_alter(&$suggestions, $vars) {
  $term = $vars['elements']['#taxonomy_term'];
  $sanitized_view_mode = strtr($vars['elements']['#view_mode'], '.', '_');

  $suggestions[] = 'taxonomy_term__' . $sanitized_view_mode;
  $suggestions[] = 'taxonomy_term__' . $term->bundle() . '__' . $sanitized_view_mode;
  $suggestions[] = 'taxonomy_term__' . $term->id() . '__' . $sanitized_view_mode;

  return $suggestions;
}

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function frost_theme_theme_suggestions_field_alter(&$suggestions, $vars) {
  // Allow templates by field formatter.
  if (isset($vars['element']['#formatter'])) {
    $suggestions[] = 'field-formatter--' . $vars['element']['#formatter'];
  }
}

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function frost_theme_theme_suggestions_page_alter(array &$suggestions, array $vars) {
  if ($node = \Drupal::routeMatch()->getParameter('node')) {
    $suggestions[] = 'page__' . $node->bundle();
  }
}

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function frost_theme_theme_suggestions_views_exposed_form_alter(array &$suggestions, array $vars) {
  $suggestions[] = 'views-exposed-form__' . $vars['form']['#id'];
}

/**
 * Implements hook_theme_suggestions_HOOK_alter().
 */
function frost_theme_theme_suggestions_fieldset_alter(array &$suggestions, array $vars) {
  if (isset($vars['element']['#parents'][0])) {
    $suggestions[] = 'fieldset--parent-' . $vars['element']['#parents'][0];
  }
  if (isset($vars['element']['#id'])) {
    $suggestions[] = 'fieldset--id-' . $vars['element']['#id'];
  }
}

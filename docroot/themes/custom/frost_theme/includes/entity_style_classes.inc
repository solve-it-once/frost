<?php

/**
 * @file
 * Auto-apply styles to entities, based on field names and other criteria.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityInterface;

/**
 * Helper function to add classes to the entity.
 *
 * @param array $vars
 *   The variables array from the parent preprocess function.
 * @param \Drupal\Core\Entity\EntityInterface $entity
 *   The entity being preprocessed.
 * @param array|null $where
 *   The array within $vars to put the style classes.
 */
function _frost_theme_entity_style_classes(array &$vars, EntityInterface $entity, array|null &$where) {
  $fields = $entity->getFieldDefinitions();

  // Put target ID selector here so background can be mutated elsewhere.
  $where[] = Html::cleanCssIdentifier('background--' . $entity->getEntityTypeId()
    . '-' . $entity->bundle() . '-' . $entity->id());

  foreach ($fields as $name => $definition) {
    $type = $definition->getType();
    $settings = $definition->getSettings();

    if (mb_substr($name, 0, 11) === 'field_style') {
      // field_style_... values as classes (typically _classes.scss)
      // Handle different field types, mainly boolean and list_string.
      if ($type === 'boolean') {
        // Make an array of on/off values.
        $switch_vals = [
          $settings['off_label'],
          $settings['on_label'],
        ];
      }

      $value = $entity->get($name)->getValue();
      foreach ($value as $val) {
        if (mb_strlen($val['value']) >= 2) {
          $where[] = Html::cleanCssIdentifier($val['value']);
        }
        else {
          if ($type === 'boolean') {
            $where[] = Html::cleanCssIdentifier($switch_vals[$val['value']]);
          }
        }
      }
    }
    elseif (in_array(mb_substr($name, 0, 11), [
      'field_image',
      'field_video',
    ])) {
      // Whether an image is present (especially for backgrounds).
      $value = $entity->get($name)->getValue();
      if (isset($value[0]['target_id'])) {
        $where[] = Html::cleanCssIdentifier('has-' . $name);
      }
    }
    elseif (in_array(mb_substr($name, 0, 16), [
      'field_components',
    ])) {
      // Whether an image is present (especially for backgrounds).
      $value = $entity->get($name)->referencedEntities();
      if (count($value)) {
        $where[] = Html::cleanCssIdentifier('has-' . $name);
      }
    }
    elseif (mb_substr($name, 0, 10) === 'field_attr') {
      // Handle the 'open' attr separately.
      if ($name === 'field_attr_open') {
        $value = $entity->get($name)->getValue();
        foreach ($value as $val) {
          if (isset($val['value']) && $val['value']) {
            $vars['attributes']['open'] = TRUE;
            break;
          }
        }
      }
      else {
        // Add field_data_.. as HTML data-*="" attributes using the field value.
        $data_attr = mb_substr($name, 11);
        $value = $entity->get($name)->getValue();
        foreach ($value as $val) {
          if (isset($val['value'])) {
            $vars['attributes'][Html::cleanCssIdentifier($data_attr)] = $val['value'];
          }
        }
      }
    }
    elseif (mb_substr($name, 0, 10) === 'field_data') {
      // Add field_data_... as HTML data-*="" attributes using the field value.
      $data_attr = mb_substr($name, 11);
      $value = $entity->get($name)->getValue();
      foreach ($value as $val) {
        if (isset($val['value'])) {
          $vars['attributes'][Html::cleanCssIdentifier('data-' . $data_attr)] = $val['value'];
        }
      }
    }
    elseif ($type === 'entity_reference_display') {
      // Set the child display mode as a class to the container.
      $value = $entity->get($name)->getValue();
      $mode = 'default';
      if (isset($value[0]['value'])) {
        $mode = $value[0]['value'];
      }

      $vars['child_mode'] = $mode;
      $where[] = Html::cleanCssIdentifier('child-display-mode--' . $mode);
    }
    elseif ($type === 'entity_reference' && isset($settings['target_type'])
      && $settings['target_type'] === 'taxonomy_term'
    ) {
      // Class nodes/etc. with tagged taxonomy terms.
      $values = $entity->get($name)->referencedEntities();
      foreach ($values as $term) {
        if (!is_null($term->label())) {
          $where[] = Html::cleanCssIdentifier('tagged-with-tid--' . $term->id());
          $where[] = Html::cleanCssIdentifier('tagged-with-vocabulary--' . $term->bundle());
        }
      }
    }
  }
}

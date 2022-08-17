<?php

/**
 * @file
 * Preprocess.
 */

use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;

/**
 * Implements hook_preprocess_entity().
 */
function frost_theme_preprocess_paragraph(&$vars) {
  $vars['html_tag'] = 'div';
  $entity = $vars['paragraph'];
  $type = 'paragraph';
  $bundle = $entity->bundle();
  $view_mode = $vars['elements']['#view_mode'];

  $vars['attributes']['class'] = [
    Html::cleanCssIdentifier('entity-type-' . $type),
    Html::cleanCssIdentifier('entity-bundle-' . $bundle),
    Html::cleanCssIdentifier('entity--' . $type . '-' . $bundle),
    Html::cleanCssIdentifier('view-mode--' . $view_mode),
    Html::cleanCssIdentifier('entity--' . $type . '-' . $bundle . '-' . $view_mode),
    Html::cleanCssIdentifier('entity-' . $bundle . '-' . $entity->id()),
  ];
  $vars['attributes']['id'] = Html::cleanCssIdentifier($type . '-' . $bundle . '-' . $entity->id());

  // Add class for parent field if present.
  if (isset($entity->referringItem) && method_exists($entity->referringItem, 'getFieldDefinition')) {
    $ref_field = $entity->_referringItem->getFieldDefinition()->getName();
    $vars['attributes']['class'][] = Html::cleanCssIdentifier('from-parent--' . $ref_field);
  }

  // Style classes by bundle (where unambiguous).
  switch ($bundle) {
    case 'accordion':
      $vars['html_tag'] = 'details';

      break;

    case 'blockquote':
      $vars['html_tag'] = 'blockquote';

      break;

    case 'card':
    case 'statistic_percent':
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('js--child--link');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex--1');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('display--flex');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('justify-content--center');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex-direction--column');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('text-align--center');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('padding-horizontal--4');

      break;

    case 'cards':
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('display--flex');
      $vars['attributes']['class'][] = HTML::cleanCssIdentifier('align-self--center');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('justify-content--center');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('layout--flex-row');

      break;

    case 'column':
      $vars['html_tag'] = 'aside';
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('display--flex');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex-direction--column');

      // Check if column is empty or has only a spacer.
      if ($entity->hasField('field_components')) {
        $values = $entity->get('field_components')->getValue();
        if (!isset($values) || !count($values)) {
          $vars['attributes']['class'][] = Html::cleanCssIdentifier('column--empty');
        }
        elseif (count($values)) {
          $column_empty = TRUE;
          foreach ($values as $value) {
            if (isset($value['target_id'])) {
              $component = \Drupal::entityTypeManager()->getStorage('paragraph')->load($value['target_id']);

              if (is_object($component) && method_exists($component, 'bundle')) {
                if ($component->bundle() !== 'spacer') {
                  $vars['attributes']['class'][] = Html::cleanCssIdentifier('contains-component--' . $component->bundle());
                  $column_empty = FALSE;
                }
              }
            }
          }

          if ($column_empty) {
            $vars['attributes']['class'][] = Html::cleanCssIdentifier('column--empty');
          }
        }
      }

      break;

    case 'heading':
      $values = $entity->get('field_heading')->getValue();
      if (isset($values[0]['wrapper'])) {
        $vars['html_tag'] = $values[0]['wrapper'];
      }
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('align-items--center');

      break;

    case 'hotspot':
      $vars['html_tag'] = 'figcaption';
      $vars['#attached']['library'][] = 'frost_theme/frost-tippyjs';
      $vars['attributes']['tabindex'] = '0';
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('display--flex');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex-direction--row');

      // Ensure anchor added in template has attributes.
      $vars['anchor_attributes'] = new Attribute([
        'aria-label' => t('Toggle hotspot caption'),
        'aria-controls' => Html::cleanCssIdentifier('toggle-' . $type . '-' . $bundle . '-' . $entity->id()),
        'class' => [
          'hotspot-anchor',
        ],
        'data-tippy-interactive' => 'true',
        'href' => '#' . Html::cleanCssIdentifier('toggle-' . $type . '-' . $bundle . '-' . $entity->id()),
        'id' => Html::cleanCssIdentifier('anchor-' . $type . '-' . $bundle . '-' . $entity->id()),
      ]);

      // Ensure content wrapper has attributes.
      $vars['content_attributes'] = new Attribute([
        'class' => [
          'hotspot-content',
        ],
        'id' => Html::cleanCssIdentifier('toggle-' . $type . '-' . $bundle . '-' . $entity->id()),
      ]);

      // Set the CSS to position the hotspot.
      $xpercent = '50';
      $ypercent = '50';
      if ($entity->hasField('field_data_xpercent')) {
        $xvalues = $entity->get('field_data_xpercent')->getValue();
        if (isset($xvalues) && count($xvalues)) {
          $xpercent = $xvalues[0]['value'];
        }
      }
      if ($entity->hasField('field_data_ypercent')) {
        $yvalues = $entity->get('field_data_ypercent')->getValue();
        if (isset($yvalues) && count($yvalues)) {
          $ypercent = $yvalues[0]['value'];
        }
      }
      $vars['attributes']['style'] = 'left:' . $xpercent . '%;top:' . $ypercent . '%;';
      break;

    case 'layout':
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('display--flex');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex-direction--row');

      break;

    case 'media':
      $vars['html_tag'] = 'figure';
      // Add zoom library. Make this conditional for better performance.
      $vars['#attached']['library'][] = 'frost_theme/frost-zoom';

      // Check if media has captions and hotspots.
      if ($entity->hasField('field_hotspots')) {
        $values = $entity->get('field_hotspots')->getValue();
        if (!isset($values) || !count($values)) {
          $vars['attributes']['class'][] = Html::cleanCssIdentifier('lacks--hotspots');
        }
        else {
          $vars['attributes']['class'][] = Html::cleanCssIdentifier('has--hotspots');
        }
      }

      if ($entity->hasField('field_text')) {
        $values = $entity->get('field_text')->getValue();
        if (!isset($values) || !count($values)) {
          $vars['attributes']['class'][] = Html::cleanCssIdentifier('lacks--caption');
        }
        else {
          $vars['attributes']['class'][] = Html::cleanCssIdentifier('has--caption');
        }
      }

      break;

    case 'media_listing':
      // Add the lightbox library when toggled.
      if ($entity->hasField('field_data_lightbox')) {
        $lightbox_bool = $entity->get('field_data_lightbox')->getValue();
        if (isset($lightbox_bool[0]['value']) && $lightbox_bool[0]['value']) {
          $vars['attributes']['class'][] = Html::cleanCssIdentifier('js--frost-fslightbox');
          $vars['#attached']['library'][] = 'frost_theme/frost-fslightbox';
        }
      }

      break;

    case 'message':
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('messages');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('js--dismissible');

      break;

    case 'stripe':
      $vars['html_tag'] = 'section';

      break;

    case 'stripe_collection':
      $field_viewmode = $entity->get('field_viewmode')->getValue();
      $child_mode = 'default';
      if (isset($field_viewmode[0]['value'])) {
        $child_mode = $field_viewmode[0]['value'];
      }

      if ($child_mode === 'tab') {
        $vars['attributes']['class'][] = Html::cleanCssIdentifier('padding-vertical--4');
      }

      break;

    case 'table':
      $vars['#attached']['library'][] = 'frost_theme/frost-highcharts';

      break;

    case 'term_listing':
    case 'content_listing':
      // Set the child display mode as a class to the container.
      $value = $entity->get('field_viewmode')->getValue();
      $mode = 'default';
      if (isset($value[0]['value'])) {
        $mode = $value[0]['value'];
      }
      $vars['child_mode'] = $mode;

      // Class out the container to make layout CSS straightforward.
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex-direction--row');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('justify-content--center');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('flex-wrap--wrap');
      $vars['attributes']['class'][] = Html::cleanCssIdentifier('layout--flex-row');

      break;
  }

  $where = &$vars['attributes']['class'];
  _frost_theme_entity_style_classes($vars, $entity, $where);
}

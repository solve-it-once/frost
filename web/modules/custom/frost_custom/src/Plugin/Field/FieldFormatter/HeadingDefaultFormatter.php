<?php

/**
 * @file
 * Contains \Drupal\frost_custom\Plugin\Field\FieldFormatter\HeadingDefaultFormatter.
 */

namespace Drupal\frost_custom\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'heading_default' formatter.
 *
 * @FieldFormatter(
 *   id = "heading_default",
 *   label = @Translation("Text as heading"),
 *   field_types = {
 *     "heading_text"
 *   }
 * )
 */
class HeadingDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = array();

    // Loop through items.
    foreach ($items as $delta => $item) {
      $element[$delta] = array(
        '#type' => 'html_tag',
        '#tag' => $item->getValue('value')['wrapper'],
        '#value' => $item->getValue('value')['value'],
      );
    }

    return $element;
  }

}

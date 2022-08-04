<?php

namespace Drupal\frost_custom\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'heading_textfield' widget.
 *
 * @FieldWidget(
 *   id = "heading_textfield",
 *   label = @Translation("Text with selector"),
 *   field_types = {
 *     "heading_text"
 *   }
 * )
 */
class HeadingTextfieldWidget extends WidgetBase implements WidgetInterface {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $settings = $this->fieldDefinition->getFieldStorageDefinition()->getSettings();
    $element += [
      '#type' => 'fieldset',
      'value' => [
        '#type' => 'textfield',
        '#default_value' => $items->getValue()[$delta]['value'] ?? '',
        '#maxlength' => $settings['max_length'],
        '#attributes' => ['class' => ['text-full']],
        '#title' => $this->t('Heading text'),
      ],
    ];
    // Get available options and limit to allowed values.
    $options = _frost_custom_get_options();
    $allowed = [];
    foreach ($settings['allowed_wrappers'] as $key => $value) {
      if ($value) {
        $allowed[$key] = 1;
      }
    }
    $options = array_intersect_key($options, $allowed);

    // Add select list for the heading type.
    $element['wrapper'] = [
      '#type' => 'select',
      '#default_value' => $items->getValue()[$delta]['wrapper'] ?? NULL,
      '#options' => $options,
      '#title' => $this->t('Heading type'),
    ];
    return $element;
  }

}

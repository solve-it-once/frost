<?php

namespace Drupal\frost_custom\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'heading_text' field type.
 *
 * @FieldType(
 *   id = "heading_text",
 *   label = @Translation("Heading"),
 *   description = @Translation("This field is short varchar, with a wrapper."),
 *   category = @Translation("Text"),
 *   default_widget = "heading_textfield",
 *   default_formatter = "heading_default"
 * )
 */
class HeadingTextField extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings(): array {
    return [
      'max_length' => 255,
      'allowed_wrappers' => [
        'h2',
        'h3',
        'h4',
        'h5',
      ],
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => $field_definition->getSetting('max_length'),
        ],
        'wrapper' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Heading text'));
    $properties['wrapper'] = DataDefinition::create('string')
      ->setLabel(t('Heading type'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data): array {
    $element = [];

    $settings = $this->getSettings();

    $element['max_length'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Maximum length'),
      '#default_value' => $settings['max_length'] ?? '',
      '#required' => TRUE,
      '#element_validate' => [
        [$this, 'formValidate'],
      ],
      '#description' => $this->t('The maximum length of the field in characters.'),
      '#disabled' => $has_data,
    ];
    $element['allowed_wrappers'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Allowed wrappers'),
      '#default_value' => $settings['allowed_wrappers'] ?? [],
      '#required' => TRUE,
      '#description' => $this->t('The list of headings to choose from.'),
      '#options' => _frost_custom_get_options(),
      '#disabled' => $has_data,
    ];
    $element += parent::storageSettingsForm($form, $form_state, $has_data);

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints(): array {
    $constraint_manager = \Drupal::typedDataManager()
      ->getValidationConstraintManager();
    $constraints = parent::getConstraints();

    if ($max_length = $this->getSetting('max_length')) {
      $constraints[] = $constraint_manager->create('ComplexData', [
        'value' => [
          'Length' => [
            'min' => 0,
            'max' => $max_length,
            'maxMessage' => $this->t('%name: the text may not be longer than @max characters.', [
              '%name' => $this->getFieldDefinition()
                ->getLabel(),
              '@max' => $max_length,
            ]),
            'minMessage' => $this->t('%name: the text must be longer than @min characters.', [
              '%name' => $this->getFieldDefinition()
                ->getLabel(),
              '@min' => 0,
            ]),
          ],
        ],
      ]);
    }

    return $constraints;
  }

  /**
   * Validate the color text field.
   */
  public function formValidate($element, FormStateInterface $form_state) {
    $settings = $this->getSettings();

    if (!empty($element['value']) && !empty($settings['max_length']) &&
      strlen($element['value']) > $settings['max_length']) {
      $message = $this->t('%name: the text may not be longer than %max characters.', [
        '%name' => $element['#title'],
        '%max' => 255,
      ]);
      $form_state->setError($element, $message);
    }
  }

}

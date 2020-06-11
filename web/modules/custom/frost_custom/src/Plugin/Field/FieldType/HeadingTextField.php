<?php

/**
 * @file
 * Contains \Drupal\frost_custom\Plugin\Field\HeadingTextField.
 */

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
  public static function defaultStorageSettings() {
    $settings = array(
      'max_length' => 255,
      'allowed_wrappers' => array(
        'h2', 'h3', 'h4', 'h5',
      ),
    ) + parent::defaultStorageSettings();

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'value' => array(
          'type' => 'varchar',
          'length' => $field_definition->getSetting('max_length'),
        ),
//        'format' => array(
//          'type' => 'varchar',
//          'length' => 255,
//          'not null' => FALSE,
//        ),
        'wrapper' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ),
      ),
//      'indexes' => array(
//        'format' => array('format'),
//      ),
//      'foreign keys' => array(
//        'format' => array(
//          'table' => 'filter_format',
//          'columns' => array('format' => 'format'),
//        ),
//      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Heading text'));
    $properties['wrapper'] = DataDefinition::create('string')
      ->setLabel(t('Heading type'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = array();

    $settings = $this->getSettings();

    $element['max_length'] = array(
      '#type' => 'textfield',
      '#title' => t('Maximum length'),
      '#default_value' => isset($settings['max_length']) ? $settings['max_length'] : '',
      '#required' => TRUE,
      '#element_validate' => array(array($this, 'formValidate'),
      ),
      '#description' => t('The maximum length of the field in characters.'),
      '#disabled' => $has_data,
    );
    $element['allowed_wrappers'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Allowed wrappers'),
      '#default_value' => isset($settings['allowed_wrappers']) ? $settings['allowed_wrappers'] : array(),
      '#required' => TRUE,
      '#description' => t('The list of headings to choose from.'),
      '#options' => _frost_custom_get_options(),
      '#disabled' => $has_data,
    );
    $element += parent::storageSettingsForm($form, $form_state, $has_data);

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();
    $constraints = parent::getConstraints();

    if ($max_length = $this->getSetting('max_length')) {
      $constraints[] = $constraint_manager->create('ComplexData', array(
        'value' => array(
          'Length' => array(
            'min' => 0,
            'max' => $max_length,
            'maxMessage' => t('%name: the text may not be longer than @max characters.', array('%name' => $this->getFieldDefinition()->getLabel(), '@max' => $max_length)),
            'minMessage' => t('%name: the text must be longer than @min characters.', array('%name' => $this->getFieldDefinition()->getLabel(), '@min' => 0)),
          ),
        ),
      ));
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
      $message = t('%name: the text may not be longer than %max characters.', array(
        '%name' => $element['#title'],
        '%max' => 255,
      ));
      $form_state->setError($element, $message);
    }
    elseif (!empty($element['value']) && strlen($element['value']) < 0) {
      $message = t('%name: the length must be a positive number.', array(
        '%name' => $element['#title'],
        '%max' => 255,
      ));
      $form_state->setError($element, $message);
    }
  }

}

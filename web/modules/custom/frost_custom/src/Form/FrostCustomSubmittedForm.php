<?php

/**
 * @file
 * Contains \Drupal\frost_custom\Form\FrostCustomSubmittedForm.
 */

namespace Drupal\frost_custom\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\node\Entity\NodeType;

/**
 * Configure custom settings for this site.
 */
class FrostCustomSubmittedForm extends ConfigFormBase {

  /**
   * Constructs a new frostCustomSubmittedForm.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'frost_custom_submitted_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['frost_custom.frost_submitted'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $sub = $this->config('frost_custom.frost_submitted');
    $content_types = NodeType::loadMultiple();

    // Make form a tree.
    $form['#tree'] = TRUE;

    // Show tokens available for a node.
    if (\Drupal::moduleHandler()->moduleExists('token')) {
      $form['tokens'] =
        \Drupal::service('token.tree_builder')->buildRenderable(array('entity', 'node'));
    }

    foreach ($content_types as $type => $info) {
      $form['frost_submitted'][$type] = array(
        '#type' => 'textarea',
        '#default_value' => $sub->get($type) !== NULL ? $sub->get($type) : 'Submitted by [node:author:name] on [node:created]',
        '#title' => t('Submitted for @type', array('@type' => $type)),
      );
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('frost_custom.frost_submitted');
    foreach ($form_state->getValue('frost_submitted') as $key => $value) {
      $config->set($key, $value);
    }

    $config->save();
    parent::submitForm($form, $form_state);
  }

}

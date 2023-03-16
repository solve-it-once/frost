<?php

namespace Drupal\frost_custom\Form;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\token\TreeBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure custom settings for this site.
 */
class FrostCustomSubmittedForm extends ConfigFormBase {

  /**
   * The module handling service, invoked in the constructor.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected ModuleHandlerInterface $moduleHandler;

  /**
   * The token tree builder service, invoked in the constructor.
   *
   * @var \Drupal\token\TreeBuilderInterface
   */
  protected TreeBuilderInterface $treeBuilder;

  /**
   * The entity type bundle info service, invoked in the constructor.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected EntityTypeBundleInfoInterface $entityTypeBundleInfo;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $moduleHandler, TreeBuilderInterface $treeBuilder, EntityTypeBundleInfoInterface $entityTypeBundleInfo) {
    parent::__construct($config_factory);
    $this->moduleHandler = $moduleHandler;
    $this->treeBuilder = $treeBuilder;
    $this->entityTypeBundleInfo = $entityTypeBundleInfo;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler'),
      $container->get('token.tree_builder'),
      $container->get('entity_type.bundle.info')
    );
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
    try {
      $content_types = $this->entityTypeBundleInfo->getBundleInfo('node');
    } catch (InvalidPluginDefinitionException|PluginNotFoundException $e) {
    }

    // Make form a tree.
    $form['#tree'] = TRUE;

    // Show tokens available for a node.
    if ($this->moduleHandler->moduleExists('token')) {
      $form['tokens'] =
        $this->treeBuilder->buildRenderable(['entity', 'node']);
    }

    foreach ($content_types as $type => $info) {
      $form['frost_submitted'][$type] = [
        '#type' => 'textarea',
        '#default_value' => $sub->get($type) ?? 'Submitted by [node:author:name] on [node:created]',
        '#title' => $this->t('Submitted for @type', ['@type' => $type]),
      ];
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

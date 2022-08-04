<?php

namespace Drupal\contact_info\Plugin\Block;

/**
 * @file
 * Contains \Drupal\contact_info\Plugin\Block/MapBlock.
 */

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block with an address.
 *
 * @Block(
 *   id = "map",
 *   admin_label = @Translation("Map"),
 * )
 */
class MapBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory, as passed through the constructor.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $configFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Grab contact info data.
    $contact_info = $this->configFactory->get('contact_info.setup');
    $map_settings = $this->getConfiguration();

    return contact_info_create_map($contact_info, $map_settings);
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    // Configuration form for map/directions block.
    $map_data = $this->getConfiguration();

    $form['api_key'] = [
      '#title' => $this->t('Google API key'),
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $map_data['api_key'] ?? '',
    ];

    $form['dimensions'] = [
      '#title' => $this->t('Dimensions'),
      '#type' => 'fieldset',
    ];

    $form['dimensions']['width'] = [
      '#title' => $this->t('Width'),
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $map_data['width'] ?? '600',
    ];

    $form['dimensions']['height'] = [
      '#title' => $this->t('Height'),
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $map_data['height'] ?? '450',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['api_key'] = $form_state->getValue(['api_key']);
    $this->configuration['width'] = $form_state->getValue(
      [
        'dimensions',
        'width',
      ]
    );
    $this->configuration['height'] = $form_state->getValue(
      [
        'dimensions',
        'height',
      ]
    );
  }

}

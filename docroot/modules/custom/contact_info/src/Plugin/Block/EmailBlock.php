<?php

namespace Drupal\contact_info\Plugin\Block;

/**
 * @file
 * Contains \Drupal\contact_info\Plugin\Block/EmailBlock.
 */

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block with an email number.
 *
 * @Block(
 *   id = "email",
 *   admin_label = @Translation("Email"),
 * )
 */
class EmailBlock extends BlockBase implements ContainerFactoryPluginInterface {

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

    return contact_info_create_email($contact_info);
  }

}

<?php

namespace Drupal\contact_info\Plugin\Block;

/**
 * @file
 * Contains \Drupal\contact_info\Plugin\Block/SocialBlock.
 */

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block with social links.
 *
 * @Block(
 *   id = "social",
 *   admin_label = @Translation("Social"),
 * )
 */
class SocialBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    $contact_info = $this->configFactory->get('contact_info.setup');

    return contact_info_create_social($contact_info);
  }

}

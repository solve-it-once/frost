<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'CopyrightBlock' block.
 *
 * @Block(
 *  id = "copyright_block",
 *  admin_label = @Translation("Copyright Notice"),
 * )
 */
class CopyrightBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    $site_name = Xss::filterAdmin($this->configFactory->get('system.site')
      ->get('name'));
    return [
      'copyright' => [
        '#markup' => '&copy; '
        . $this->t('Copyright')
        . ' '
        . date('Y')
        . ' '
        . $site_name
        . '. '
        . $this->t('All rights reserved'),
      ],
    ];
  }

}

<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a "Back to top" button.
 *
 * @Block(
 *   id = "back_to_top_block",
 *   admin_label = @Translation("Back to top"),
 * )
 */
class BackToTopBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The module handling service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected ModuleHandlerInterface $moduleHandler;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ModuleHandlerInterface $moduleHandler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $path = $this->moduleHandler->getModule('frost_custom')->getPath();
    $image = [
      '#theme' => 'image',
      '#style_name' => 'original',
      '#uri' => $path . '/back_to_top/arrow-up.svg',
      '#alt' => $this->t('Back to the top'),
    ];

    // Render array that returns button.
    return [
      'icon' => $image,
      'text' => [
        '#markup' => $this->t('Back to the top'),
      ],
      '#attached' => [
        'library' => [
          'frost_custom/back_to_top',
        ],
      ],
      '#atrributes' => [
        'class' => ['back-to-top'],
      ],
    ];
  }

}

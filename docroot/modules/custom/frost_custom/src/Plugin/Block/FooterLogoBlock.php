<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a logo for the footer.
 *
 * @Block(
 *   id = "inverted_logo_block",
 *   admin_label = @Translation("Inverted Logo"),
 * )
 */
class FooterLogoBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The theme handling service.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected ThemeHandlerInterface $themeHandler;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ThemeHandlerInterface $themeHandler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->themeHandler = $themeHandler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('theme_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $path = $this->themeHandler->getTheme('frost_theme')->getPath();
    $image = [
      '#prefix' => '<a href="/">',
      '#theme' => 'image',
      '#style_name' => 'original',
      '#uri' => $path . '/images/footer-logo.png',
      '#alt' => $this->t('Return to the home page'),
      '#suffix' => '</a>',
    ];

    // Render array that returns button.
    return [
      'icon' => $image,
      '#atrributes' => [
        'class' => ['footer-logo'],
      ],
    ];
  }

}

<?php

/**
 * @file
 * Contains \Drupal\frost_custom\Plugin\Block\FooterLogoBlock.
 */

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a logo for the footer.
 *
 * @Block(
 *   id = "inverted_logo_block",
 *   admin_label = @Translation("Inverted Logo"),
 * )
 */
class FooterLogoBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $theme_handler = \Drupal::service('theme_handler');
    $path = $theme_handler->getTheme('frost_theme')->getPath();
    $image = [
      '#prefix' => '<a href="/">',
      '#theme' => 'image',
      '#style_name' => 'original',
      '#uri' => $path . '/images/footer-logo.png',
      '#alt' => t('Return to the home page'),
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

<?php

/**
 * @file
 * Contains \Drupal\frost_custom\Plugin\Block\BackToTopBlock.
 */

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a "Back to top" button.
 *
 * @Block(
 *   id = "back_to_top_block",
 *   admin_label = @Translation("Back to top"),
 * )
 */
class BackToTopBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $module_handler = \Drupal::service('module_handler');
    $path = $module_handler->getModule('frost_custom')->getPath();
    $image = [
      '#theme' => 'image',
      '#style_name' => 'original',
      '#uri' => $path . '/back_to_top/arrow-up.svg',
      '#alt' => t('Back to the top'),
    ];

    // Render array that returns button.
    return [
      'icon' => $image,
      'text' => [
        '#markup' => t('Back to the top'),
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

<?php

namespace Drupal\contact_info\Plugin\Block;

/**
 * @file
 * Contains \Drupal\contact_info\Plugin\Block/SocialBlock.
 */

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block with social links.
 *
 * @Block(
 *   id = "social",
 *   admin_label = @Translation("Social"),
 * )
 */
class SocialBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $contact_info = \Drupal::config('contact_info.setup');

    return contact_info_create_social($contact_info);
  }

}

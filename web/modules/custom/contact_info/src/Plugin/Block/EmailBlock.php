<?php

namespace Drupal\contact_info\Plugin\Block;

/**
 * @file
 * Contains \Drupal\contact_info\Plugin\Block/EmailBlock.
 */

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block with an email number.
 *
 * @Block(
 *   id = "email",
 *   admin_label = @Translation("Email"),
 * )
 */
class EmailBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Grab contact info data.
    $contact_info = \Drupal::config('contact_info.setup');

    return contact_info_create_email($contact_info);
  }

}

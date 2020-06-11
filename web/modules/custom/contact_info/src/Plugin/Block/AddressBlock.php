<?php

namespace Drupal\contact_info\Plugin\Block;

/**
 * @file
 * Contains \Drupal\contact_info\Plugin\Block/AddressBlock.
 */

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block with an address.
 *
 * @Block(
 *   id = "address",
 *   admin_label = @Translation("Address"),
 * )
 */
class AddressBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Grab contact info data.
    $contact_info = \Drupal::config('contact_info.setup');

    // Set up variables to send to the theme function.
    $variables = contact_info_create_address($contact_info);

    return $variables;
  }

}

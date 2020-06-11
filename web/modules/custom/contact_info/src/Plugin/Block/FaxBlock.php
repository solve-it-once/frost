<?php

namespace Drupal\contact_info\Plugin\Block;

/**
 * @file
 * Contains \Drupal\contact_info\Plugin\Block/FaxBlock.
 */

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block with an fax number.
 *
 * @Block(
 *   id = "fax",
 *   admin_label = @Translation("Fax"),
 * )
 */
class FaxBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Grab contact info data.
    $contact_info = \Drupal::config('contact_info.setup');

    return contact_info_create_fax($contact_info);
  }

}

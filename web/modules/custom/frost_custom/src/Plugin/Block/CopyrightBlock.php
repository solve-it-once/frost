<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'CopyrightBlock' block.
 *
 * @Block(
 *  id = "copyright_block",
 *  admin_label = @Translation("Copyright Notice"),
 * )
 */
class CopyrightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $site_name = Xss::filterAdmin(\Drupal::config('system.site')->get('name'));
    return [
      'copyright' => [
        '#markup' => '&copy; Copyright ' . date('Y') . ' US Ecology, Inc. All rights reserved' ,
      ],
    ];
  }

}

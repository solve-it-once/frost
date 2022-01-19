<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Block\BlockBase;

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

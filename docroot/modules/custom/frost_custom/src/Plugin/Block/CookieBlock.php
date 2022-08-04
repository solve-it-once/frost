<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CookieBlock' block.
 *
 * @Block(
 *  id = "cookie_block",
 *  admin_label = @Translation("Cookie notice"),
 * )
 */
class CookieBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#attributes' => [
        'aria-describedby' => 'dialog--cookie-disclosure--description',
        'aria-label' => 'Cookie consent',
        'aria-live' => 'polite',
        'class' => [
          'background-color--main-dark',
          'bottom--0',
          'color--white',
          'dialog',
          'dialog--cookie-disclosure',
          'js--dismissible',
          'left--0',
          'padding-horizontal--4',
          'padding-vertical--4',
          'position--fixed',
          'position--relative',
          'right--0',
          'z-index--4',
        ],
        'role' => 'dialog',
      ],
      'cookie' => [
        '#prefix' => '<div class="layout--stripe--inner"><span id="dialog--cookie-disclosure--description" class="dialog--cookie-disclosure--description">',
        '#markup' => 'This website uses cookies to ensure you get the best visiting experience. <a href="/privacy-policy">Read our Privacy Policy</a> or '
        . '<a href="https://www.cookiesandyou.com" rel="external noopener noreferrer" target="_blank">Learn more about cookies</a>',
        '#suffix' => '</span></div>',
      ],
    ];
  }

}

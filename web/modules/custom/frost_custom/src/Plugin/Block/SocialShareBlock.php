<?php

/**
 * @file
 * Contains \Drupal\frost_custom\Plugin\Block\SocialShareBlock.
 */

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Component\Utility\Html;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;

/**
 * Provides current page social share links.
 *
 * @Block(
 *   id = "social_share_block",
 *   admin_label = @Translation("Social share icons"),
 * )
 */
class SocialShareBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    global $base_url;
    $request = \Drupal::request();
    $route_match = \Drupal::routeMatch();
    $current_path = $request->getRequestUri();
    $current_url = $base_url . $current_path;
    $title = \Drupal::service('title_resolver')
      ->getTitle($request, $route_match->getRouteObject());
    if (gettype($title) === 'object') {
      $title = $title->render();
      $title = strip_tags($title);
    }
    else {
      if (isset($title['#markup'])) {
        $title = Html::escape($title['#markup']);
      }
    }

    $facebook_link = $this->buildSocialLink('https://www.facebook.com/sharer/sharer.php', 'facebook', [
      'query' => [
        'u' => $current_url,
      ],
    ]);
    $twitter_link = $this->buildSocialLink('https://twitter.com/intent/tweet', 'twitter', [
      'query' => [
        'url' => $current_url,
        'title' => $title,
      ],
    ]);
    $linkedin_link = $this->buildSocialLink('https://www.linkedin.com/shareArticle', 'linkedin', [
      'query' => [
        'url' => $current_url,
        'title' => $title,
      ],
    ]);
    $email = $this->buildSocialLink('mailto:', 'gmail', [
      'query' => [
        'subject' => $title,
        'body' => $current_url,
      ],
    ]);

    // Render array that returns button.
    return [
      '#cache' => [
        'contexts' => ['url'],
      ],
      '#type' => 'container',
      '#attributes' => [
        'class' => ['current-page-links'],
      ],
      'share' => [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['social-share'],
        ],
        'list' => [
          '#theme' => 'item_list',
          '#list_type' => 'ul',
          '#attributes' => [
            'class' => [
              'social',
              'display--flex',
              'list-style--none',
            ],
          ],
          '#items' => [
            $facebook_link,
            $twitter_link,
            $linkedin_link,
            $email,
          ],
        ],
      ],
    ];
  }

  /**
   * A Helper function to create the link render array for social channels.
   *
   * @param $share_link
   *   The direct link to share on the specific channel.
   * @param $share_channel
   *   The human name of the channel so we can give it a title and class.
   * @param $query
   *   The specific query parameters we need to include.
   *
   * @return array
   *   The link render array for this social channel.
   */
  private function buildSocialLink($share_link, $share_channel, $query) {
    $class = Html::cleanCssIdentifier($share_channel);
    return [
      '#type' => 'link',
      '#url' => Url::fromUri($share_link, [
        'query' => $query,
        'attributes' => [
          'class' => ['icon', $class],
        ],
      ]),
      '#title' => [
        '#markup' => $this->svg_icon($class . '.svg'),
      ],
    ];
  }

  /**
   * Return an SVG icon as markup based on filename.
   *
   * @param $filename
   *
   * @return \Drupal\Component\Render\MarkupInterface|string
   */
  public function svg_icon($filename) {
    if (!isset($filename)) {
      $filename = 'drupal.svg';
    }
    $icon_path = DRUPAL_ROOT . '/libraries/simple-icons/icons/' . $filename;

    // Get the icon's file contents.
    $icon = file_get_contents($icon_path);

    // Return the icon as a markup object (@todo determine XSS risk)
    return Markup::create($icon);
  }

}

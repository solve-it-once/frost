<?php

/**
 * @file
 * Contains \Drupal\frost_custom\Plugin\Block\SocialShareBlock.
 */

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Component\Utility\Html;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides current page social share links and a favorite placeholder.
 *
 * @Block(
 *   id = "social_share_block",
 *   admin_label = @Translation("Share and favorites"),
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
    $title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());
    if (gettype($title) === 'object') {
      $title = $title->render();
      $title = strip_tags($title);
    }
    else if (isset($title['#markup'])) {
      $title = Html::escape($title['#markup']);
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
    $linkedin_link = $this->buildSocialLink('http://www.linkedin.com/shareArticle', 'linkedin', [
      'query' => [
        'url' => $current_url,
        'title' => $title,
      ],
    ]);
    $email = [
      '#type' => 'html_tag',
      '#tag' => 'a',
      '#attributes' => [
        'class' => ['icon', 'envelope'],
        'href' => 'mailto:?subject=' . urlencode($title) . '&body=' . urlencode($current_url),
      ],
      '#value' => t('Email'),
    ];

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
        'icon' => [
          '#prefix' => '<span class="icon share">',
          '#markup' => t('Share'),
          '#suffix' => '</span>',
        ],
        'list' => [
          '#theme' => 'item_list',
          '#list_type' => 'ul',
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
    $channel = ucfirst($share_channel);
    $class = Html::cleanCssIdentifier($share_channel);
    return [
      '#type' => 'link',
      '#url' => Url::fromUri($share_link, [
        'query' => $query,
        'attributes' => [
          'class' => ['icon', $class],
        ],
      ]),
      '#title' => t('@channel', ['@channel' => $channel]),
    ];
  }

}

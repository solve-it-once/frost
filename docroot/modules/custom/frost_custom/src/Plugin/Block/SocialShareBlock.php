<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Component\Utility\Html;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Controller\TitleResolverInterface;
use Drupal\Core\Http\RequestStack;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides current page social share links.
 *
 * @Block(
 *   id = "social_share_block",
 *   admin_label = @Translation("Social share icons"),
 * )
 */
class SocialShareBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The request stack.
   *
   * @var \Drupal\Core\Http\RequestStack
   */
  protected RequestStack $requestStack;

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected RouteMatchInterface $routeMatch;

  /**
   * The title resolver service.
   *
   * @var \Drupal\Core\Controller\TitleResolverInterface
   */
  protected TitleResolverInterface $titleResolver;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RequestStack $requestStack, RouteMatchInterface $routeMatch, TitleResolverInterface $titleResolver) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->requestStack = $requestStack;
    $this->routeMatch = $routeMatch;
    $this->titleResolver = $titleResolver;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack'),
      $container->get('current_route_match'),
      $container->get('title_resolver')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    global $base_url;
    $request = $this->requestStack->getCurrentRequest();
    $current_path = $request->getRequestUri();
    $current_url = $base_url . $current_path;
    $title = $this->titleResolver
      ->getTitle($request, $this->routeMatch->getRouteObject());
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
   * @param string $share_link
   *   The direct link to share on the specific channel.
   * @param string $share_channel
   *   The human name of the channel so we can give it a title and class.
   * @param array $query
   *   The specific query parameters we need to include.
   *
   * @return array
   *   The link render array for this social channel.
   */
  private function buildSocialLink(string $share_link, string $share_channel, array $query) {
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
        '#markup' => $this->svgIcon($class . '.svg'),
      ],
    ];
  }

  /**
   * Return an SVG icon as markup based on filename.
   *
   * @param string $filename
   *   The name of the file where an SVG can be found.
   *
   * @return \Drupal\Component\Render\MarkupInterface|string
   *   Markup or a string of the SVG in the file.
   */
  public function svgIcon(string $filename) {
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

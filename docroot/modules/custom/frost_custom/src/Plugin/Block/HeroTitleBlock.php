<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\TitleBlockPluginInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides either the current page's hero or the page title.
 *
 * @Block(
 *   id = "hero_title_block",
 *   admin_label = @Translation("Hero or Page Title"),
 * )
 */
class HeroTitleBlock extends BlockBase implements TitleBlockPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The route match service for the current request.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected RouteMatchInterface $routeMatch;

  /**
   * The service for displaying entities.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected EntityDisplayRepositoryInterface $entityDisplayRepository;

  /**
   * The services for managing entities overall.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $routeMatch, EntityDisplayRepositoryInterface $entityDisplayRepository, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $routeMatch;
    $this->entityDisplayRepository = $entityDisplayRepository;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('entity_display.repository'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * The page title: a string (plain title) or a render array (formatted title).
   *
   * @var string|array|null
   */
  protected string|array|null $title = '';

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->title = $title;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $return = [
      '#cache' => [
        'contexts' => ['url'],
      ],
      '#attributes' => [
        'class' => [
          'entity-bundle-stripe',
          'hero-title--page-title',
          'text-align--center',
          'background-color--grey-dark',
          'background-image--default',
          'color--white',
        ],
      ],
      '#type' => 'page_title',
      '#title' => $this->title,
    ];

    // If page entity has a hero, make block contents that instead.
    $parameters = $this->routeMatch->getParameters()->all();
    $paged_entity_types = ['commerce_product', 'node', 'taxonomy_term', 'user'];
    foreach ($paged_entity_types as $type) {
      if (isset($parameters[$type])) {
        $entity = $parameters[$type];
        $bundle = (method_exists($entity, 'bundle')) ? $entity->bundle() : 'unknown';

        // Get the enabled view modes.
        $view_displays = $this->entityDisplayRepository->getViewModeOptionsByBundle($type, $bundle);

        // Determine if there is a stripe paragraph in the field_hero to use.
        if (method_exists($entity, 'hasField') && $entity->hasField('field_hero')) {
          $field_hero = $entity->get('field_hero')->getValue();
        }

        if (isset($field_hero) && count($field_hero)) {
          // Set the block content to the hero paragraph if appropriate.
          $return = [
            '#cache' => [
              'contexts' => ['url'],
            ],
            'field' => $entity->get('field_hero')->view([
              'label' => 'hidden',
              'type' => 'entity_reference_entity_view',
              'settings' => [
                'view_mode' => 'default',
                'link' => FALSE,
              ],
            ]),
          ];
        }
        elseif (array_key_exists('hero', $view_displays)) {
          // If the page does not have a hero paragraph stripe, try using the
          // content type's 'hero' view mode if set.
          $view_builder = $this->entityTypeManager->getViewBuilder($type);
          $return = [
            '#cache' => [
              'contexts' => ['url'],
            ],
            '#attributes' => [
              'class' => [],
            ],
            'field' => $view_builder->view($entity, 'hero'),
          ];
        }
      }
    }

    return $return;
  }

}

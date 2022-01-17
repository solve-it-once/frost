<?php

/**
 * @file
 * Contains \Drupal\frost_custom\Plugin\Block\HeroTitleBlock.
 */

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\TitleBlockPluginInterface;
use Drupal\Core\Entity\EntityDisplayRepository;

/**
 * Provides either the current page's hero or the page title.
 *
 * @Block(
 *   id = "hero_title_block",
 *   admin_label = @Translation("Hero or Page Title"),
 * )
 */
class HeroTitleBlock extends BlockBase implements TitleBlockPluginInterface {

  /**
   * The page title: a string (plain title) or a render array (formatted title).
   *
   * @var string|array
   */
  protected $title = '';

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
          'text-align--center',
          'background-color--grey-light',
          'background-image--default',
          'color--main-dark',
        ],
      ],
      '#type' => 'page_title',
      '#title' => $this->title,
    ];

    // If page entity has a hero, make block contents that instead.
    $parameters = \Drupal::routeMatch()->getParameters()->all();
    $paged_entity_types = ['node', 'taxonomy_term', 'commerce_product'];
    foreach ($paged_entity_types as $type) {
      if (isset($parameters[$type])) {
        $entity = $parameters[$type];
        $bundle = 'unknown';
        if (method_exists($entity, 'bundle')) {
          $bundle = $entity->bundle();
        }
        $id = 0;
        if (method_exists($entity, 'id')) {
          $id = $entity->id();
        }

        // Get the enabled view modes.
        $view_displays = \Drupal::service('entity_display.repository')->getViewModeOptionsByBundle($type, $bundle);

        if (method_exists($entity, 'hasField') && $entity->hasField('field_hero')) {
          $field_hero = $entity->get('field_hero')->getValue();
        }

        if (isset($field_hero) && count($field_hero)) {
          // Set the block content to the hero if appropriate.
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
          $view_builder = \Drupal::entityTypeManager()->getViewBuilder($type);
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

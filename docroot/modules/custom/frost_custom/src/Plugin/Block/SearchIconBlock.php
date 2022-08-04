<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'SearchIconBlock' block.
 *
 * @Block(
 *  id = "search_icon_block",
 *  admin_label = @Translation("Search Icon Block"),
 * )
 */
class SearchIconBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected FormBuilderInterface $formBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $formBuilder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Form\EnforcedResponseException
   * @throws \Drupal\Core\Form\FormAjaxException
   */
  public function build() {
    // Create search form to add to render.
    $view = Views::getView('search');

    $view->setDisplay('block_1');
    $view->initHandlers();

    // Get the search form anew, so things like autocomplete work.
    $form_state = new FormState();
    $form_state->setStorage([
      'view' => $view,
      'display' => $view->display_handler->display,
      'rerender' => TRUE,
    ])->setMethod('get')
      ->setAlwaysProcess()
      ->disableRedirect();
    $form_state->set('rerender', NULL);

    $form = $this->formBuilder
      ->buildForm('\Drupal\views\Form\ViewsExposedForm', $form_state);

    // Search page must have the /search path alias.
    $form['#action'] = '/search';

    // Render search form.
    $build['search_icon_block'] = [
      '#markup' => '<span class="search-icon icon magnifying-glass"></span>',
      'form_wrapper' => [
        '#type' => 'container',
        '#attributes' => [
          'class' => [
            'search-form-wrapper',
            'view-search',
          ],
        ],
        'form' => $form,
      ],
    ];

    return $build;

  }

}

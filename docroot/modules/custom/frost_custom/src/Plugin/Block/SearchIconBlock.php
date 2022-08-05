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
    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15.7 13.3l-3.81-3.83A5.93 5.93 0 0013 6c0-3.31-2.69-6-6-6S1 2.69 1 6s2.69 6 6 6c1.3 0 2.48-.41 3.47-1.11l3.83 3.81c.19.2.45.3.7.3.25 0 .52-.09.7-.3a.996.996 0 000-1.41v.01zM7 10.7c-2.59 0-4.7-2.11-4.7-4.7 0-2.59 2.11-4.7 4.7-4.7 2.59 0 4.7 2.11 4.7 4.7 0 2.59-2.11 4.7-4.7 4.7z"/></svg>';
    $build['search_icon_block'] = [
      '#attributes' => [
        'class' => [
          'block--search-icon',
        ],
      ],
      'icon' => [
        '#type' => 'inline_template',
        '#allowed_tags' => [
          'button',
          'path',
          'span',
          'svg',
        ],
        '#template' => '<button aria-expanded="false" '
        . 'class="js--toggle-below button--search-widget--toggle" type="button">'
        . $icon
        . '<span class="js--toggle-below--label a11y--visually-hidden">Open below</span>'
        . '</button>',
      ],
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

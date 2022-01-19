<?php

namespace Drupal\frost_custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormState;
use Drupal\views\Views;


/**
 * Provides a 'SearchIconBlock' block.
 *
 * @Block(
 *  id = "search_icon_block",
 *  admin_label = @Translation("Search Icon Block"),
 * )
 */
class SearchIconBlock extends BlockBase {

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

    $form = \Drupal::formBuilder()
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

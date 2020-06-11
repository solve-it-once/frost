<?php

namespace Drupal\contact_info\Form;

/**
 * @file
 * Contains \Drupal\contact_info\Form\ContactInfoSetupForm.
 */

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Configure custom settings for this site.
 */
class ContactInfoSetupForm extends ConfigFormBase {

  /**
   * Constructs a new ContactInfoSetupForm.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contact_info_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['contact_info.setup'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load existing contact info.
    $contact_info = \Drupal::config('contact_info.setup');

    // Make that form a tree.
    $form['#tree'] = TRUE;

    $form['contact_info']['address'] = [
      '#type' => 'details',
      '#title' => t('Address'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $options = \Drupal::service('country_manager')->getList();
    $form['contact_info']['address']['country_name'] = [
      '#type' => 'select',
      '#default_value' => $contact_info->get('address.country_name') ? $contact_info->get('address.country_name') : 'US',
      '#title' => t('Country'),
      '#options' => $options,
    ];
    $form['contact_info']['address']['legal_name'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.legal_name') ? $contact_info->get('address.legal_name') : '',
      '#title' => t('Legal Name'),
    ];
    $form['contact_info']['address']['street_address'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.street_address') ? $contact_info->get('address.street_address') : '',
      '#title' => t('Street address'),
    ];
    $form['contact_info']['address']['street_address_2'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.street_address_2') ? $contact_info->get('address.street_address_2') : '',
      '#title' => t('Street address 2'),
    ];
    $form['contact_info']['address']['locality'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.locality') ? $contact_info->get('address.locality') : '',
      '#title' => t('Locality'),
      '#description' => t('The city, township, or administrative area'),
    ];
    $form['contact_info']['address']['region'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.region') ? $contact_info->get('address.region') : '',
      '#title' => t('Region'),
      '#description' => t("For instance, MI if you're located in Michigan"),
    ];
    $form['contact_info']['address']['postal_code'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.postal_code') ? $contact_info->get('address.postal_code') : '',
      '#title' => t('Postal code'),
    ];

    // Phone.
    $form['contact_info']['phone'] = [
      '#type' => 'details',
      '#title' => t('Phone'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $form['contact_info']['phone']['country_code'] = [
      '#type' => 'textfield',
      '#title' => t('Country code'),
      '#default_value' => $contact_info->get('phone.country_code') ? $contact_info->get('phone.country_code') : '',
      '#size' => 4,
    ];
    $form['contact_info']['phone']['number'] = [
      '#type' => 'textfield',
      '#title' => t('Number'),
      '#default_value' => $contact_info->get('phone.number') ? $contact_info->get('phone.number') : '',
    ];

    // Fax.
    $form['contact_info']['fax'] = [
      '#type' => 'details',
      '#title' => t('Fax'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $form['contact_info']['fax']['number'] = [
      '#type' => 'textfield',
      '#title' => t('Number'),
      '#default_value' => $contact_info->get('fax.number') ? $contact_info->get('fax.number') : '',
    ];

    // Email.
    $form['contact_info']['email'] = [
      '#type' => 'details',
      '#title' => t('Email'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $form['contact_info']['email']['emailaddress'] = [
      '#type' => 'textfield',
      '#title' => t('Email Address'),
      '#default_value' => $contact_info->get('email.emailaddress') ? $contact_info->get('email.emailaddress') : '',
    ];

    // Social SEO.
    $form['contact_info']['social_seo'] = [
      '#type' => 'details',
      '#title' => t('Social SEO'),
      '#description' => t('Social links and accounts that aid search engines.'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $form['contact_info']['social_seo']['site_twitter'] = [
      '#type' => 'textfield',
      '#title' => t('Site Twitter'),
      '#description' => t('The Twitter handle for the whole site, like @ao5357'),
      '#default_value' => $contact_info->get('social_seo.site_twitter') ? $contact_info->get('social_seo.site_twitter') : '',
    ];
    $form['contact_info']['social_seo']['site_google_plus'] = [
      '#type' => 'textfield',
      '#title' => t('Google+ Page'),
      '#description' => t("URL to site's Google+ page, like https://plus.google.com/103212176392756352243/"),
      '#default_value' => $contact_info->get('social_seo.site_google_plus') ? $contact_info->get('social_seo.site_google_plus') : '',
    ];
    $form['contact_info']['social_rows'] = [
      '#type' => 'hidden',
      '#value' => count($contact_info->get('social.table')),
    ];

    // Social media.
    $form['contact_info']['social'] = [
      '#type' => 'details',
      '#title' => t('Social media'),
      '#prefix' => '<div id="contact-info-social-media-wrapper">',
      '#suffix' => '</div>',
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];

    _contact_info_social_draggable_table($contact_info->get('social.table'), $form, $form_state);

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('contact_info.setup');
    $config->set('address.country_name', $form_state->getValue(
      [
        'contact_info',
        'address',
        'country_name',
      ]))->set(
      'address.legal_name', $form_state->getValue(
      [
        'contact_info',
        'address',
        'legal_name',
      ]))->set(
      'address.street_address', $form_state->getValue(
      [
        'contact_info',
        'address',
        'street_address',
      ]))->set(
      'address.street_address_2', $form_state->getValue(
      [
        'contact_info',
        'address',
        'street_address_2',
      ]))->set(
      'address.locality', $form_state->getValue(
      [
        'contact_info',
        'address',
        'locality',
      ]))->set(
      'address.region', $form_state->getValue(
      [
        'contact_info',
        'address',
        'region',
      ]))->set(
      'address.postal_code', $form_state->getValue(
      [
        'contact_info',
        'address',
        'postal_code',
      ]))->set(
      'phone.country_code', $form_state->getValue(
      [
        'contact_info',
        'phone',
        'country_code',
      ]))->set(
      'phone.number', $form_state->getValue(
      [
        'contact_info',
        'phone',
        'number',
      ]))->set(
      'fax.number', $form_state->getValue(
      [
        'contact_info',
        'fax',
        'number',
      ]))->set(
      'email.emailaddress', $form_state->getValue(
      [
        'contact_info',
        'email',
        'emailaddress',
      ]))->set(
      'social_seo.site_twitter', $form_state->getValue(
      [
        'contact_info',
        'social_seo',
        'site_twitter',
      ]))->set(
      'social_seo.site_google_plus', $form_state->getValue(
      [
        'contact_info',
        'social_seo',
        'site_google_plus',
      ]));

    foreach ($form_state->getValue([
      'contact_info',
      'social',
      'table',
    ]) as $key => $value) {
      if (!empty($value['name']) && !empty($value['url'])) {
        $config->set('social.table.' . $key, $value);
      }
    }

    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * Submit function for adding additional social media rows.
   *
   * {@inheritdoc}
   */
  public static function socialRows($form, &$form_state) {
    return $form['contact_info']['social'];
  }

}

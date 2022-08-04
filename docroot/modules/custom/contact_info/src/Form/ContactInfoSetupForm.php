<?php

namespace Drupal\contact_info\Form;

/**
 * @file
 * Contains \Drupal\contact_info\Form\ContactInfoSetupForm.
 */

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Locale\CountryManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure custom settings for this site.
 */
class ContactInfoSetupForm extends ConfigFormBase {

  /**
   * The country manager service passed from the constructor.
   *
   * @var \Drupal\Core\Locale\CountryManagerInterface
   */
  protected CountryManagerInterface $countryManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, CountryManagerInterface $countryManager) {
    $this->setConfigFactory($config_factory);
    $this->countryManager = $countryManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('country_manager')
    );
  }

  /**
   * Submit function for adding additional social media rows.
   *
   * {@inheritdoc}
   */
  public static function socialRows($form, &$form_state) {
    return $form['contact_info']['social'];
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
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load existing contact info.
    $contact_info = $this->config('contact_info.setup');

    // Make that form a tree.
    $form['#tree'] = TRUE;

    $form['contact_info']['address'] = [
      '#type' => 'details',
      '#title' => $this->t('Address'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $options = $this->countryManager->getList();
    $form['contact_info']['address']['country_name'] = [
      '#type' => 'select',
      '#default_value' => $contact_info->get('address.country_name') ? $contact_info->get('address.country_name') : 'US',
      '#title' => $this->t('Country'),
      '#options' => $options,
    ];
    $form['contact_info']['address']['legal_name'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.legal_name') ? $contact_info->get('address.legal_name') : '',
      '#title' => $this->t('Legal Name'),
    ];
    $form['contact_info']['address']['street_address'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.street_address') ? $contact_info->get('address.street_address') : '',
      '#title' => $this->t('Street address'),
    ];
    $form['contact_info']['address']['street_address_2'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.street_address_2') ? $contact_info->get('address.street_address_2') : '',
      '#title' => $this->t('Street address 2'),
    ];
    $form['contact_info']['address']['locality'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.locality') ? $contact_info->get('address.locality') : '',
      '#title' => $this->t('Locality'),
      '#description' => $this->t('The city, township, or administrative area'),
    ];
    $form['contact_info']['address']['region'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.region') ? $contact_info->get('address.region') : '',
      '#title' => $this->t('Region'),
      '#description' => $this->t("For instance, MI if you're located in Michigan"),
    ];
    $form['contact_info']['address']['postal_code'] = [
      '#type' => 'textfield',
      '#default_value' => $contact_info->get('address.postal_code') ? $contact_info->get('address.postal_code') : '',
      '#title' => $this->t('Postal code'),
    ];

    // Phone.
    $form['contact_info']['phone'] = [
      '#type' => 'details',
      '#title' => $this->t('Phone'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $form['contact_info']['phone']['country_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country code'),
      '#default_value' => $contact_info->get('phone.country_code') ? $contact_info->get('phone.country_code') : '',
      '#size' => 4,
    ];
    $form['contact_info']['phone']['number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Number'),
      '#default_value' => $contact_info->get('phone.number') ? $contact_info->get('phone.number') : '',
    ];

    // Fax.
    $form['contact_info']['fax'] = [
      '#type' => 'details',
      '#title' => $this->t('Fax'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $form['contact_info']['fax']['number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Number'),
      '#default_value' => $contact_info->get('fax.number') ? $contact_info->get('fax.number') : '',
    ];

    // Email.
    $form['contact_info']['email'] = [
      '#type' => 'details',
      '#title' => $this->t('Email'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $form['contact_info']['email']['emailaddress'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email Address'),
      '#default_value' => $contact_info->get('email.emailaddress') ? $contact_info->get('email.emailaddress') : '',
    ];

    // Social SEO.
    $form['contact_info']['social_seo'] = [
      '#type' => 'details',
      '#title' => $this->t('Social SEO'),
      '#description' => $this->t('Social links and accounts that aid search engines.'),
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    $form['contact_info']['social_seo']['site_twitter'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site Twitter'),
      '#description' => $this->t('The Twitter handle for the whole site, like @ao5357'),
      '#default_value' => $contact_info->get('social_seo.site_twitter') ? $contact_info->get('social_seo.site_twitter') : '',
    ];
    $form['contact_info']['social_seo']['site_google_plus'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Google+ Page'),
      '#description' => $this->t("URL to site's Google+ page, like https://plus.google.com/103212176392756352243/"),
      '#default_value' => $contact_info->get('social_seo.site_google_plus') ? $contact_info->get('social_seo.site_google_plus') : '',
    ];
    $form['contact_info']['social_rows'] = [
      '#type' => 'hidden',
      '#value' => count($contact_info->get('social.table')),
    ];

    // Social media.
    $form['contact_info']['social'] = [
      '#type' => 'details',
      '#title' => $this->t('Social media'),
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

    // Empty the table so only the form rows appear.
    $config->clear('social.table');
    // Go through applicable form rows and save.
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
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['contact_info.setup'];
  }

}

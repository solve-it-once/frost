<?php

/**
 * @file
 * Tokens for the contact info module.
 */

use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function contact_info_token_info() {
  $info = [];

  // Define new token types.
  $info['types']['contact_info'] = [
    'name' => t('Contact info'),
    'description' => t('Data from the contact info module.'),
  ];
  $info['types']['address'] = [
    'name' => t('Address'),
    'description' => t('Address from the contact info module.'),
    'needs-data' => 'address',
  ];
  $info['types']['phone'] = [
    'name' => t('Phone'),
    'description' => t('Phone from the contact info module.'),
    'needs-data' => 'phone',
  ];
  $info['types']['fax'] = [
    'name' => t('Fax'),
    'description' => t('Fax from the contact info module.'),
    'needs-data' => 'fax',
  ];
  $info['types']['email'] = [
    'name' => t('Email'),
    'description' => t('Email from the contact info module.'),
    'needs-data' => 'email',
  ];
  $info['types']['social_seo'] = [
    'name' => t('Social SEO'),
    'description' => t('Social SEO from the contact info module.'),
    'needs-data' => 'social_seo',
  ];

  // Define new tokens.
  $info['tokens']['contact_info']['address'] = [
    'name' => t('Address'),
    'description' => t('The address entered in the contact info form.'),
    'type' => 'address',
  ];
  $info['tokens']['address']['country_name'] = [
    'name' => t('Country name'),
    'description' => t('The country name of the address.'),
  ];
  $info['tokens']['address']['legal_name'] = [
    'name' => t('Legal name'),
    'description' => t('The legal name of the address.'),
  ];
  $info['tokens']['address']['street_address'] = [
    'name' => t('Street address'),
    'description' => t('The street address of the address.'),
  ];
  $info['tokens']['address']['street_address_2'] = [
    'name' => t('Street address, line 2'),
    'description' => t('The second line of the street address of the address.'),
  ];
  $info['tokens']['address']['locality'] = [
    'name' => t('Locality'),
    'description' => t('The locality of the address.'),
  ];
  $info['tokens']['address']['region'] = [
    'name' => t('Region'),
    'description' => t('The region of the address.'),
  ];
  $info['tokens']['address']['postal_code'] = [
    'name' => t('Postal code'),
    'description' => t('The postal code of the address.'),
  ];

  $info['tokens']['contact_info']['phone'] = [
    'name' => t('Phone number'),
    'description' => t('The phone number entered in the contact info form.'),
    'type' => 'phone',
  ];
  $info['tokens']['phone']['label'] = [
    'name' => t('Label'),
    'description' => t('The label of the phone number.'),
  ];
  $info['tokens']['phone']['country_code'] = [
    'name' => t('Country code'),
    'description' => t('The country code of the phone number.'),
  ];
  $info['tokens']['phone']['number'] = [
    'name' => t('Number'),
    'description' => t('The number of the phone number.'),
  ];

  $info['tokens']['contact_info']['fax'] = [
    'name' => t('Fax number'),
    'description' => t('The fax number entered in the contact info form.'),
    'type' => 'fax',
  ];
  $info['tokens']['fax']['label'] = [
    'name' => t('Label'),
    'description' => t('The label of the fax number.'),
  ];
  $info['tokens']['fax']['number'] = [
    'name' => t('Number'),
    'description' => t('The number of the fax number.'),
  ];

  $info['tokens']['contact_info']['email'] = [
    'name' => t('Email address'),
    'description' => t('The email address entered in the contact info form.'),
    'type' => 'email',
  ];
  $info['tokens']['email']['label'] = [
    'name' => t('Label'),
    'description' => t('The label of the email address.'),
  ];
  $info['tokens']['email']['emailaddress'] = [
    'name' => t('Email Address'),
    'description' => t('The address of the email.'),
  ];

  $info['tokens']['contact_info']['social_seo'] = [
    'name' => t('Social SEO'),
    'description' => t('The social info useful for metatag and elsewhere.'),
    'type' => 'social_seo',
  ];
  $info['tokens']['social_seo']['site_twitter'] = [
    'name' => t('Site Twitter'),
    'description' => t('The site twitter handle.'),
  ];
  $info['tokens']['social_seo']['site_google_plus'] = [
    'name' => t('Site Google+'),
    'description' => t('The site Google+ URL.'),
  ];

  $info['tokens']['contact_info']['social'] = [
    'name' => t('Social links'),
    'description' => t('The social links entered in the contact info form.'),
  ];

  $info['tokens']['contact_info']['map'] = [
    'name' => t('Map'),
    'description' => t('Google map embed.'),
  ];

  return $info;
}

/**
 * Implements hook_tokens().
 */
function contact_info_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $replacements = [];

  if ($type == 'contact_info') {
    // Grab contact info data.
    $contact_info = Drupal::config('contact_info.setup');
    $renderer = Drupal::service('renderer');
    // Loop through available tokens.
    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'address':
          $create_address = contact_info_create_address($contact_info);
          $replacements[$original] = $renderer->renderPlain($create_address);
          break;

        case 'phone':
          $create_phone = contact_info_create_phone($contact_info);
          $replacements[$original] = $renderer->renderPlain($create_phone);
          break;

        case 'fax':
          $create_fax = contact_info_create_fax($contact_info);
          $replacements[$original] = $renderer->renderPlain($create_fax);
          break;

        case 'email':
          $create_email = contact_info_create_email($contact_info);
          $replacements[$original] = $renderer->renderPlain($create_email);
          break;

        case 'social_seo':
          $replacements[$original] = print_r($contact_info->get('social_seo'), TRUE);
          break;

        case 'social':
          $social_block = contact_info_create_social($contact_info);
          $replacements[$original] = $renderer->renderPlain($social_block);
          break;

        case 'map':
          $map = contact_info_create_map($contact_info);
          $replacements[$original] = $renderer->renderPlain($map);
      }
    }

    // Chained tokens.
    $token_service = Drupal::token();
    $addresses = $token_service->findWithPrefix($tokens, 'address');
    foreach ($addresses as $name => $original) {
      $replacements[$original] = $contact_info->get('address')[$name];
    }

    $phones = $token_service->findWithPrefix($tokens, 'phone');
    foreach ($phones as $name => $original) {
      $replacements[$original] = $contact_info->get('phone')[$name];
    }

    $faxes = $token_service->findWithPrefix($tokens, 'fax');
    foreach ($faxes as $name => $original) {
      $replacements[$original] = $contact_info->get('fax')[$name];
    }

    $emails = $token_service->findWithPrefix($tokens, 'email');
    foreach ($emails as $name => $original) {
      $replacements[$original] = $contact_info->get('email')[$name];
    }

    $social_seos = $token_service->findWithPrefix($tokens, 'social_seo');
    foreach ($social_seos as $name => $original) {
      $replacements[$original] = $contact_info->get('social_seo')[$name];
    }
  }

  return $replacements;
}

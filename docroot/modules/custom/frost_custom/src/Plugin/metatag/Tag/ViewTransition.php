<?php

namespace Drupal\frost_custom\Plugin\metatag\Tag;

use Drupal\metatag\Plugin\metatag\Tag\MetaNameBase;

/**
 * Add in the view-transition meta tag.
 *
 * @MetatagTag(
 *   id = "view_transition",
 *   label = @Translation("View transition"),
 *   description = @Translation("Set to 'same-origin' to enable cross-fade between pages."),
 *   name = "view-transition",
 *   group = "advanced",
 *   weight = "4",
 *   type = "label",
 *   secure = FALSE,
 *   multiple = FALSE
 * )
 */
class ViewTransition extends MetaNameBase {
  // Inherits everything from the base class.
}

<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_input__button().
 */
function epa_intranet_preprocess_input__button(&$variables) {
  $variables['attributes']['class'][] = 'usa-button';
}

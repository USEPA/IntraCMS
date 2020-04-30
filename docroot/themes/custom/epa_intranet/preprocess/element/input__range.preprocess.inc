<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_input__range().
 */
function epa_intranet_preprocess_input__range(&$variables) {
  $variables['attributes']['class'][] = 'usa-range';
  $variables['attributes']['class'][] = 'usa-input';
}

<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_input__tel().
 */
function epa_intranet_preprocess_input__tel(&$variables) {
  $variables['attributes']['class'][] = 'usa-input';
}

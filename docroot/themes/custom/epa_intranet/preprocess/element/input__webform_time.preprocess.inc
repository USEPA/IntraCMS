<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_input__webform_time().
 */
function epa_intranet_preprocess_input__webform_time(&$variables) {
  $variables['attributes']['class'][] = 'usa-input';
  $variables['attributes']['class'][] = 'form-input';
}

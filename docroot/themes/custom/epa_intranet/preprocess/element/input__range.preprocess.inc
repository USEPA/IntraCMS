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
  $variables['attributes']['class'][] = 'form-input';
  if (isset($variables['attributes']['min'])) {
    $variables['attributes']['aria-valuemin'] = $variables['attributes']['min'];
  }
  if (isset($variables['attributes']['max'])) {
    $variables['attributes']['aria-valuemax'] = $variables['attributes']['max'];
  }
  if (isset($variables['attributes']['value'])) {
    $variables['attributes']['aria-valuenow'] = $variables['attributes']['value'];
  }

}

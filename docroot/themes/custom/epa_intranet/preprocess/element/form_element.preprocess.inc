<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_form_element().
 */
function epa_intranet_preprocess_form_element(&$variables) {
  if ($variables['type'] === 'checkbox' && isset($variables['label'])) {
    $variables['label']['#attributes']['class'][] = 'usa-checkbox__label';
  }
  if ($variables['type'] === 'radio' && isset($variables['label'])) {
    $variables['label']['#attributes']['class'][] = 'usa-radio__label';
  }
}

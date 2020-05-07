<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_form_element_label().
 */
function epa_intranet_preprocess_form_element_label(&$variables) {
  $variables['attributes']['class'][] = 'usa-sr-label';
  $element_number = 0;
  foreach($variables['attributes']['class'] as $class){
    if ($class === 'usa-label'){
      array_splice($variables['attributes']['class'], $element_number, 1);
      break;
    }
    $element_number++;
  }
  if (!empty($variables['required'])) {
    $variables['attributes']['class'][] = 'usa-input--required';
  }
}

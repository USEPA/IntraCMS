<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_input__checkbox().
 */
function epa_intranet_preprocess_input__checkbox(&$variables) {
  $variables['attributes']['class'][] = 'usa-checkbox__input';
}

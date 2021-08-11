<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_input__textfield().
 */
function epa_intranet_preprocess_input__textfield(&$variables) {
    $variables['attributes']['class'][] = 'usa-input';
    $variables['attributes']['class'][] = 'form-input';
}

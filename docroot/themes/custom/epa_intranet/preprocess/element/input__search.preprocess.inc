<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_input__search().
 */
function epa_intranet_preprocess_input__search(&$variables) {
    $variables['attributes']['class'][] = 'usa-input';
    $variables['attributes']['class'][] = 'form-input';
}

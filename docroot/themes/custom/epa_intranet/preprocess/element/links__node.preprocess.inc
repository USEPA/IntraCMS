<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Implements hook_preprocess_links__node().
 */
function epa_intranet_preprocess_links__node(&$variables) {
    if ($variables['links']) {
        foreach (array_keys($variables['links']) as $key) {
            $classes = [];
            if (isset($variables['links'][$key]['link']['#options']['attributes']['class'])) {
                $classes = $variables['links'][$key]['link']['#options']['attributes']['class'];
                if (is_string($classes)) {
                    $classes = [$classes];
                }
            }
            $classes[] = 'usa-button epa-intranet-button';
            $variables['links'][$key]['link']['#options']['attributes']['class'] = $classes;
        }
    }
}

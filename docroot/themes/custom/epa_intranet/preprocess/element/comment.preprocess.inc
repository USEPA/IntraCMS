<?php

/**
 * @file
 * Preprocess function for this hook.
 */

/**
 * Preprocess theme_comment()
 */
function epa_intranet_preprocess_comment(&$variables) {
  // Remove all HTML tags from the title
  $variables['title'] = strip_tags($variables['title']);
}

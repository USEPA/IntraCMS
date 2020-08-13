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
  $created_date = $variables['comment']->getCreatedTime();
  $changed_date = $variables['comment']->getChangedTime();
  $formatted_created = format_date($created_date, 'custom', 'D, m/d/Y - g:ia');
  $formatted_changed = format_date($changed_date, 'custom', 'D, m/d/Y - g:ia');

  $variables['created'] = $formatted_created;
  $variables['changed'] = $formatted_changed;
  $variables['title'] = strip_tags($variables['title']);
}

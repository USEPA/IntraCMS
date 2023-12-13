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
  $formatted_created = date('D, m/d/Y - g:ia', $created_date);
  $formatted_changed = date('D, m/d/Y - g:ia', $changed_date);

  $variables['created'] = $formatted_created;
  $variables['changed'] = $formatted_changed;
  $variables['title'] = strip_tags($variables['title']);
}

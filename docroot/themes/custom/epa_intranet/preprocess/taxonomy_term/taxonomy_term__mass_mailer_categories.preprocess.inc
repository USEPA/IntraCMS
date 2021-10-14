<?php

/**
 * Implements hook_preprocess_taxonomy_term__mass_mailer_categories().
 */
function epa_intranet_preprocess_taxonomy_term__mass_mailer_categories(&$variables) {

  if (isset($variables['term']) && isset($variables['term']->field_banner)) {
    if (!$variables['term']->field_banner->isEmpty()) {
    $img = $variables['term']->field_banner->entity->url();

    $css = ".mass-mailer-banner {
    background-image:url($img);
    background-position:center;
    background-size:cover;
    height:300px;
     }";
    $tag = [
      '#tag' => 'style',
      '#value' => $css,
    ];
    $variables['#attached']['html_head'][] = [$tag,'style'];

    $variables['attributes']['class'][] = 'mass-mailer-banner';
    }
  }
}

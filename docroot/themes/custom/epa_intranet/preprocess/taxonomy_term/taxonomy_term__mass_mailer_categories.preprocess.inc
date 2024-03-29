<?php

/**
 * Implements hook_preprocess_taxonomy_term__mass_mailer_categories().
 */
function epa_intranet_preprocess_taxonomy_term__mass_mailer_categories(&$variables) {

  if (isset($variables['term']) && isset($variables['term']->field_banner)) {
    if (!$variables['term']->field_banner->isEmpty()) {
    $img = $variables['term']->field_banner->entity->createFileUrl();

    $css = ".mass-mailer-banner {
    background-image:url($img);
    background-position:center;
    background-size:cover;
    height:250px;
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

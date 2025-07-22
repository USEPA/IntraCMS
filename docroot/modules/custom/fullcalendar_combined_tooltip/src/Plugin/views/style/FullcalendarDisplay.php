<?php

namespace Drupal\fullcalendar_combined_tooltip\Plugin\views\style;

use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin which displays a FullCalendar with taxonomy term color support.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "fullcalendar_display",
 *   title = @Translation("FullCalendar with Taxonomy Color"),
 *   help = @Translation("FullCalendar display with taxonomy term color support."),
 *   theme = "views_view_fullcalendar_display",
 *   display_types = {"normal"}
 * )
 */
class FullcalendarDisplay extends \Drupal\fullcalendar\Plugin\views\style\FullcalendarDisplay {

  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['taxonomy_color'] = [
      '#type' => 'details',
      '#title' => $this->t('Color by Taxonomy Term'),
      '#open' => TRUE,
    ];

    $form['taxonomy_color']['taxonomy_field'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Taxonomy Reference Field'),
      '#default_value' => $this->options['taxonomy_color']['taxonomy_field'] ?? '',
      '#description' => $this->t('Machine name of the taxonomy reference field (e.g., field_event_type).'),
    ];

    $form['taxonomy_color']['term_colors'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Term ID to Color Mapping'),
      '#default_value' => $this->options['taxonomy_color']['term_colors'] ?? '',
      '#description' => $this->t('Enter term ID and color (hex) on each line like: 23:#269c2a'),
    ];
  }

  public function validateOptionsForm(&$form, FormStateInterface $form_state) {
    parent::validateOptionsForm($form, $form_state);
  }

  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    parent::submitOptionsForm($form, $form_state);

    $this->options['taxonomy_color']['taxonomy_field'] = $form_state->getValue(['taxonomy_color', 'taxonomy_field']);
    $this->options['taxonomy_color']['term_colors'] = $form_state->getValue(['taxonomy_color', 'term_colors']);
  }

}

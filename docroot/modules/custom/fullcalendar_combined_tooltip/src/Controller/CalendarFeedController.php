<?php


namespace Drupal\fullcalendar_combined_tooltip\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;

class CalendarFeedController {
  public function feed() {
    $events = [];

    // Only taxonomy term ID 23 gets a special color
    $special_term_id = 23;
    $special_color = '#269c2a';   // Green for EPA
    $default_color = '#3a87ad';   // Blue for all other events

    // Your taxonomy reference field
    $taxonomy_field = 'field_event_host';

    // Load all published event nodes
    $nids = \Drupal::entityQuery('node')
      ->accessCheck(TRUE)
      ->condition('status', 1)
      ->condition('type', 'event')
      ->execute();

    $nodes = Node::loadMultiple($nids);

    foreach ($nodes as $node) {
      $start = date(DATE_ATOM, strtotime($node->get('field_event_date')->value));
      $description = $node->get('field_event_description')->value ?? '';

      // Default background
      $backgroundColor = $default_color;
      $borderColor = $default_color;
      $textColor = '#ffffff';

      // Check if the event uses the special taxonomy term
      if ($node->hasField($taxonomy_field) && !$node->get($taxonomy_field)->isEmpty()) {
        $terms = $node->get($taxonomy_field)->referencedEntities();
        if (!empty($terms)) {
          $term_id = $terms[0]->id();
          if ($term_id == $special_term_id) {
            $backgroundColor = $special_color;
            $borderColor = $special_color;
          }
        }
      }

      $events[] = [
        'title' => $node->label(),
        'start' => $start,
        'url' => $node->toUrl()->toString(),
        'backgroundColor' => $backgroundColor,
        'borderColor' => $borderColor,
        'textColor' => $textColor,
        'extendedProps' => [
          'description' => $description,
        ],
      ];
    }

    return new JsonResponse($events);
  }
}

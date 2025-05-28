<?php

namespace Drupal\fullcalendar_combined_tooltip\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;

class CalendarFeedController {
  public function feed() {
    $events = [];
    $nids = \Drupal::entityQuery('node')
      ->accessCheck(TRUE)
      ->condition('status', 1)
      ->condition('type', 'event')
      ->execute();

    $nodes = Node::loadMultiple($nids);
    foreach ($nodes as $node) {
      //$start = $node->get('field_event_date')->value; //change the correct event date field name
	  $start = date(DATE_ATOM, strtotime($node->get('field_event_date')->value));
      $description = $node->get('field_event_description')->value ?? '';
      $events[] = [
        'title' => $node->label(),
        'start' => $start,
        'url' => $node->toUrl()->toString(),
        'extendedProps' => [
          'description' => $description,
        ],
      ];
    }

    return new JsonResponse($events);
  }
}

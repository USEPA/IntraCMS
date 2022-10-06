<?php

namespace Drupal\epa_web_areas\Utility;

use Drupal\Core\Cache\Cache;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class AliasBatch.
 */
class AliasBatch {

  use StringTranslationTrait;

  /**
   * Batch op to update aliases.
   *
   * @param array $entities
   *   Entities to batch update aliases.
   */
  public function startAliasBatch(array $entities) {
    $class = 'Drupal\epa_web_areas\Utility\AliasBatch';
    $batch = [
      'title' => $this->t('Updating related content entities path aliases.'),
      'operations' => [
        [
          [$class, 'updateAliases'],
          [$entities],
        ],
      ],
      'error_message' => $this->t('An error occured during processing'),
      'finished' => [$class, 'finishedUpdateAliases'],
    ];
    batch_set($batch);
  }

  /**
   * Batch op to update aliases.
   *
   * @param array $entities
   *   Entities associated with group.
   * @param array $context
   *   Provide messages during batch.
   */
  public static function updateAliases(array $entities, array &$context) {
    if (!isset($context['sandbox']['current'])) {
      $context['sandbox'] = [
        'current' => 0,
        'max' => count($entities),
      ];
      $context['results']['updated'] = 0;
      $context['results']['tags'] = [];
    }
    $sandbox = &$context['sandbox'];
    $results = &$context['results'];

    $limit = 5;
    $entities = array_slice($entities, $sandbox['current'], $limit);

    foreach ($entities as $entity) {
      $saved = \Drupal::service('pathauto.generator')->updateEntityAlias($entity, 'update');
      if ($saved) {
        $results['updated']++;
        $results['tags'] = array_merge($results['tags'], $entity->getCacheTagsToInvalidate());
      }
      $sandbox['current']++;
    }

    $context['message'] = t('Processed @current of @total', [
      '@current' => $sandbox['current'],
      '@total' => $sandbox['max'],
    ]);

    $context['finished'] = empty($sandbox['max']) ? 1 : ($sandbox['current'] / $sandbox['max']);
  }

  /**
   * Reports the results of update alias operation.
   *
   * @param bool $success
   *   Whether or not batch finished successfully.
   * @param array $results
   *   Results passed from operations.
   */
  public static function finishedUpdateAliases(bool $success, array $results) {
    // The 'success' parameter means no fatal PHP errors were detected.
    $message = t('No aliases to update.');
    if ($success) {
      $updated = $results['updated'];
      if ($updated) {
        $message = \Drupal::translation()->formatPlural(
          $results['updated'],
          'One alias updated.',
          '@count aliases updated.'
        );
      }
    }
    else {
      $message = t('Finished with an error.');
    }

    Cache::invalidateTags($results['tags']);

    \Drupal::messenger()->addStatus($message);
  }

}

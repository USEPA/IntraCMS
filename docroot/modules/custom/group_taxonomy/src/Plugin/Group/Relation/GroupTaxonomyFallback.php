<?php

namespace Drupal\group_taxonomy\Plugin\Group\Relation;

/**
 * Provides a zero-dependency temporary fallback mapping plugin for group_taxonomy.
 *
 * @GroupRelationType(
 *   id = "group_taxonomy",
 *   label = @Translation("Temporary Group Taxonomy Fallback"),
 *   description = @Translation("Bypasses 9203 container initialization blocks."),
 *   entity_type_id = "taxonomy_term",
 *   pretty_path_key = "taxonomy"
 * )
 */
class GroupTaxonomyFallback {
  // Empty class with zero external dependencies to prevent Class Not Found compilation crashes.
  public function __construct() {}
  public function __call($name, $arguments) { return null; }
  public static function __callStatic($name, $arguments) { return null; }
}

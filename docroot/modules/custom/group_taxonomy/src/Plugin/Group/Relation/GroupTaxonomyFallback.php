<?php

namespace Drupal\group_taxonomy\Plugin\Group\Relation;

use Drupal\group\Plugin\Group\Relation\GroupRelationTypeBase;

/**
 * Provides a temporary fallback mapping plugin for group_taxonomy.
 *
 * @GroupRelationType(
 *   id = "group_taxonomy",
 *   label = @Translation("Temporary Group Taxonomy Fallback"),
 *   description = @Translation("Bypasses 9203 container initialization blocks."),
 *   entity_type_id = "taxonomy_term",
 *   pretty_path_key = "taxonomy"
 * )
 */
class GroupTaxonomyFallback extends GroupRelationTypeBase {
  // Overrides base definitions to return empty placeholders during the 9203 row migration loop
  public function getLabel() { return 'Group Taxonomy Fallback'; }
  public function getDescription() { return ''; }
}

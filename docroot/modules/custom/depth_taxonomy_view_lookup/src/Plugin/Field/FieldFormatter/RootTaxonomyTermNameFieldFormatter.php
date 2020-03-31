<?php

namespace Drupal\depth_taxonomy_view_lookup\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\taxonomy\TermInterface;

/**
 * Plugin implementation of the 'entity reference taxonomy term RSS' formatter.
 *
 * @FieldFormatter(
 *   id = "entity_reference_root_term_name",
 *   label = @Translation("Root term name"),
 *   description = @Translation("Display label of the root term for this term ancestry."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class RootTaxonomyTermNameFieldFormatter extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    /** @var \Drupal\taxonomy\Entity\Term $item */
    $item = $items->getEntity();
    $ret = '';
    if ($item instanceof TermInterface) {
      $ancestors = \Drupal::service('entity_type.manager')->getStorage("taxonomy_term")->loadAllParents($item->id());
      /** @var \Drupal\taxonomy\Entity\Term $root */
      $root = end($ancestors);
      $ret = $root->getName();
    }
    return [['#markup' => $ret]];
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
// This formatter is only available for taxonomy terms.
    return $field_definition->getFieldStorageDefinition()->getSetting('target_type') == 'taxonomy_term';
  }
}

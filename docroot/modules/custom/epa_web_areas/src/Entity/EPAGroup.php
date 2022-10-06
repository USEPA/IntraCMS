<?php

namespace Drupal\epa_web_areas\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\group\Entity\Group;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Extends entity for group.
 */
class EPAGroup extends Group {

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);

    // If group is updated, group type is web area, and machine name
    // or title is changed bulk update associated group content entities.
    $bundle = $this->bundle();
    if ($update === TRUE && $bundle == 'web_area') {
      if (($this->get('field_machine_name')->isEmpty()
          && $this->matchesOriginal('field_machine_name')
          && $this->label() != $this->original->label())
          || !$this->matchesOriginal('field_machine_name')
      ) {
        $entities = $this->getContentEntities();
        \Drupal::service('epa_web_areas.alias_batch')->startAliasBatch($entities);
      }
    }

    // If group is inserted, group type is web area, and
    // homepage field is empty, after save, create a web area home page node.
    if ($update === FALSE
        && $bundle == 'web_area'
        && $this->get('field_homepage')->isEmpty()
    ) {
      // Create body paragraph
      $paragraph = Paragraph::create([
        'type' => 'html',
        'field_body' => [
          'value'  =>  '',
          'format' => 'filtered_html'
        ],
      ]);
      $paragraph->save();

      // Create node.
      $node = Node::create([
        'type' => 'web_area',
        'title' => $this->label(),
      ]);
      $node->field_paragraphs->appendItem($paragraph);
      $node->save();

      // Add node to group via entity reference and as group content.
      $this->addContent($node, 'group_node:web_area');
      $this->field_homepage->target_id = $node->id();
      $this->save();

      \Drupal::messenger()->addStatus(t('Node %label has been created.', ['%label' => $node->toLink()->toString()]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function matchesOriginal($field_name) {
    return $this->get($field_name)->value == $this->original->get($field_name)->value;
  }

}

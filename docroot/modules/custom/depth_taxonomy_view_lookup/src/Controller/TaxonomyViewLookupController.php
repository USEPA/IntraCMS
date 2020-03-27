<?php

namespace Drupal\depth_taxonomy_view_lookup\Controller;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Defines TaxonomyViewLookupController class.
 */
class TaxonomyViewLookupController extends ControllerBase {

  private $renderer;
  private $entity_manager;
  private $view_types;

  public function __construct(Renderer $renderer, EntityTypeManager $entity_manager) {
    $this->view_types = ['card', 'grid', 'table'];
    $this->renderer = $renderer;
    $this->entity_manager = $entity_manager;
  }

  private function isViewType($arg) {
    return in_array($arg, $this->view_types);
  }

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function redirectToView($parent_term, $arg_1 = null, $arg_2 = null, $arg_3 = null, $view_type = null) {
    $args = func_get_args();
    $parent_term = array_shift($args);
    $taxonomy_service = $this->entity_manager->getStorage("taxonomy_term");
    $properties = [
      'vid' => 'organization',
      'name' => $parent_term
    ];
    $terms = $taxonomy_service->loadByProperties($properties);
    $parent_term = array_values($terms)[0];
    $view_type = 'card';
    foreach ($args as $arg) {
      if (isset($arg)) {
        if ($this->isViewType($arg)) {
          $view_type = $arg;
        } else {
          $properties = $this->generate_taxonomy_properties($parent_term, $arg);
          $terms = $taxonomy_service->loadByProperties($properties);
          if (count($terms) > 0) {
            $parent_term = array_values($terms)[0];
          } else {
            throw new NotFoundHttpException();
          }
        }
      }
    }
    $view = $this->renderer->render(views_embed_view('organizations_with_staff', $view_type, $parent_term->id()));
    return [
      '#markup' => $view,
      '#title' => 'test'
    ];
  }

  public function getTitle() {
    $path = \Drupal::request()->getpathInfo();
    $taxonomy_terms_in_path = explode('/', $path);
    return $taxonomy_terms_in_path[count($taxonomy_terms_in_path) - 1];
  }


  public static function create(ContainerInterface $container) {
    $renderer = $container->get('renderer');
    $entity_manager = $container->get('entity_type.manager');
    return new static($renderer, $entity_manager);
  }

  /**
   * @param $parent_term
   * @param $name
   * @return array
   */
  public function generate_taxonomy_properties($parent_term, $name): array {
    $properties = [
      'vid' => 'organization',
      'name' => $name,
      'parent' => $parent_term->id()
    ];
    return $properties;
  }

}
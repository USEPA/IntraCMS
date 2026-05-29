<?php
/**
 * export-menus.php
 * Final robust script parsing config entities properly via getEntity()
 * Place this file in your Drupal root directory.
 */

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// 1. Bootstrap Drupal Core
$autoloader = require_once __DIR__ . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$kernel->preHandle($request);

try {
    $storage = \Drupal::entityTypeManager()->getStorage('group_content');
    
    $query = $storage->getQuery()
        ->accessCheck(FALSE)
        ->condition('type', '%group_menu%', 'LIKE');
    $ids = $query->execute();

    $export_data = [];

    if (!empty($ids)) {
        $group_contents = $storage->loadMultiple($ids);
        
        foreach ($group_contents as $group_content) {
            $menu_id = NULL;
            
            // 1. CORRECT API METHOD: Ask the relationship entity to load its linked configuration entity
            if (method_exists($group_content, 'getEntity')) {
                $referenced_config_entity = $group_content->getEntity();
                if ($referenced_config_entity) {
                    $menu_id = $referenced_config_entity->id(); // Yields string name e.g. "main"
                }
            }

            // Fallback 1: Direct property lookup alternative
            if (empty($menu_id) && $group_content->hasField('entity_id')) {
                $menu_id = $group_content->get('entity_id')->getString();
            }

            // Fallback 2: Default string fallback if everything else fails
            if (empty($menu_id)) {
                $menu_id = 'unknown_menu_id_or_empty';
            }

            // Parse group ID
            $group_id = null;
            if (method_exists($group_content, 'getGroup')) {
                $group_id = $group_content->getGroup()->id();
            } elseif ($group_content->hasField('gid')) {
                $group_id = $group_content->get('gid')->target_id;
            }

            $export_data[] = [
                'group_content_id' => $group_content->id(),
                'group_id'         => $group_id,
                'menu_id'          => $menu_id, 
                'type'             => $group_content->bundle(),
            ];
        }
    }

    // 3. Stream download directly to the browser
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="export_group_menus.json"');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo json_encode($export_data, JSON_PRETTY_PRINT);
    exit;

} catch (\Exception $e) {
    header('Content-Type: text/html; charset=utf-8');
    print "<h3>Export Failed with an Exception:</h3>";
    print "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

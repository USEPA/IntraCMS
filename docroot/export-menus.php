<?php
/**
 * export-menus.php
 * Robust script for Drupal Group 1.x using the Entity API.
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
    
    // 1. Diagnostics: Count TOTAL group content items on your local site
    $total_query = $storage->getQuery()->accessCheck(FALSE);
    $total_count = $total_query->count()->execute();
    
    // 2. Run the specific group menu query
    $query = $storage->getQuery()
        ->accessCheck(FALSE)
        ->condition('type', '%group_menu%', 'LIKE');
    $ids = $query->execute();

    // If it found items, stream the JSON file normally
    if (!empty($ids)) {
        $export_data = [];
        $group_contents = $storage->loadMultiple($ids);
        
        foreach ($group_contents as $group_content) {
            $menu_id = NULL;
            if ($group_content->hasField('entity_id_str_value') && !$group_content->get('entity_id_str_value')->isEmpty()) {
                $menu_id = $group_content->get('entity_id_str_value')->value;
            } elseif ($group_content->hasField('entity_config_id') && !$group_content->get('entity_config_id')->isEmpty()) {
                $menu_id = $group_content->get('entity_config_id')->value;
            } elseif ($group_content->hasField('entity_id') && !$group_content->get('entity_id')->isEmpty()) {
                $menu_id = $group_content->get('entity_id')->target_id;
            }

            if (empty($menu_id)) { $menu_id = 'unknown_menu_id_or_empty'; }

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

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="export_group_menus.json"');
        echo json_encode($export_data, JSON_PRETTY_PRINT);
        exit;
    }

    // 3. If empty, print a helpful debug screen instead of downloading a blank file
    header('Content-Type: text/html; charset=utf-8');
    print "<h2>Database Connection Successful!</h2>";
    print "<p>The script connected fine, but found 0 group menu records.</p>";
    print "<ul>";
    print "<li><strong>Total Group Content entities found (any type):</strong> " . $total_count . "</li>";
    print "<li><strong>Group Menu specific relations found:</strong> 0</li>";
    print "</ul>";
    print "<p><em>If Total is greater than 0, your local site has groups/content, but no group menus have been assigned yet.</em></p>";
    exit;

} catch (\Exception $e) {
    header('Content-Type: text/html; charset=utf-8');
    print "<h3>Export Failed with an Exception:</h3>";
    print "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

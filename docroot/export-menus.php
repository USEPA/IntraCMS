<?php
/**
 * export-menus.php
 * Place this file in your Drupal root directory.
 */

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// 1. Locate the autoloader and bootstrap Drupal
$autoloader = require_once __DIR__ . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$kernel->preHandle($request);

// 2. Your script can now use \Drupal::database() natively
$query = \Drupal::database()->select('group_content_field_data', 'gcfd');
$query->fields('gcfd', ['id', 'gid', 'entity_id', 'type']);
$query->condition('gcfd.type', '%group_menu%', 'LIKE');
$results = $query->execute()->fetchAll();

$export_data = [];
foreach ($results as $row) {
    $export_data[] = [
        'group_content_id' => $row->id,
        'group_id' => $row->gid,
        'menu_id' => $row->entity_id,
        'type' => $row->type,
    ];
}

// 3. Output as browser download
header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename="export_group_menus.json"');
echo json_encode($export_data, JSON_PRETTY_PRINT);
exit;

<?php
/**
 * export_group_menus.php
 * Browser-executable script to export Group 1.x menu records into a portable backup file.
 */

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// SECURITY LOCK: CHANGE THIS PASSWORD BEFORE UPLOADING!
define('ACCESS_TOKEN', 'MySecretToken2026');

if (!isset($_GET['token']) || $_GET['token'] !== ACCESS_TOKEN) {
  header('HTTP/1.0 403 Forbidden');
  print '<h1>403 Forbidden</h1>Access denied.';
  exit;
}

$autoloader = require_once __DIR__ . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$kernel->preHandle($request);

print "<pre>\nStarting Group Menu Export...\n" . str_repeat('-', 50) . "\n";

$query = \Drupal::database()->select('group_content_field_data', 'gc');
$query->addField('gc', 'id', 'group_content_id');
$query->addField('gc', 'gid', 'group_id');
$query->addField('gc', 'entity_id_str', 'menu_id'); 
$query->addField('gc', 'type', 'type');
$query->condition('gc.type', '%menu%', 'LIKE');
$results = $query->execute()->fetchAll(PDO::FETCH_ASSOC);

if (empty($results)) {
  print "WARNING: No group menu relationships found.\n";
} else {
  file_put_contents(__DIR__ . '/export_group_menus.json', json_encode($results, JSON_PRETTY_PRINT));
  print "SUCCESS: Exported " . count($results) . " group menu items to export_group_menus.json\n";
}

print str_repeat('-', 50) . "\nExport Complete.\n</pre>";

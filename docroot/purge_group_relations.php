<?php
/**
 * purge_group_relations.php
 * Run on stable Group 1.x. Deletes the relationship entities linking groups 
 * to menus so they are not orphaned when the module is uninstalled.
 */

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

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

print "<pre>\nStarting Content Relation Purge...\n" . str_repeat('-', 50) . "\n";

try {
  $storage = \Drupal::entityTypeManager()->getStorage('group_content');
  
  $bundle_id = 'web_area-group_menu-menu'; 
  
  $entities = $storage->loadByProperties(['type' => $bundle_id]);
  
  if (!empty($entities)) {
    $count = count($entities);
    $storage->delete($entities);
    print "SUCCESS: Safely deleted {$count} legacy group_content relation entities.\n";
  } else {
    print "Notice: No legacy relations found matching bundle '{$bundle_id}'.\n";
  }
} catch (\Exception $e) {
  print "FATAL ERROR during relation purge: " . $e->getMessage() . "\n";
}

print str_repeat('-', 50) . "\nPurge complete.\n</pre>";

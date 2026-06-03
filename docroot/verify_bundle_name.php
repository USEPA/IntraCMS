<?php
/**
 * verify_bundle_name.php
 * Standalone browser script to discover the exact bundle machine IDs 
 * used by your group menu relations in the database.
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

print "<pre>\nAnalyzing Database Group Content Type Distribution...\n" . str_repeat('-', 60) . "\n";

try {
  $query = \Drupal::database()->select('group_content_field_data', 'gc');
  $query->addField('gc', 'type');
  $query->addExpression('COUNT(gc.id)', 'relation_count');
  $query->condition('gc.type', '%menu%', 'LIKE');
  $query->groupBy('gc.type');
  $results = $query->execute()->fetchAll(PDO::FETCH_ASSOC);

  if (!empty($results)) {
    print "FOUND ACTIVE BUNDLE CANDIDATES:\n\n";
    print str_pad("BUNDLE TYPE MACHINE ID", 40) . " | ACTIVE RELATIONS COUNT\n";
    print str_repeat('-', 60) . "\n";
    foreach ($results as $row) {
      print str_pad($row['type'], 40) . " | " . $row['relation_count'] . "\n";
    }
  } else {
    print "NOTICE: No active group menu relationship bundles found containing data.\n";
  }
} catch (\Exception $e) {
  print "DATABASE QUERY ERROR: " . $e->getMessage() . "\n";
}
print "</pre>";

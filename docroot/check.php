<?php
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// 1. Bootstrap Drupal from the docroot
$autoloader = require_once __DIR__ . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$kernel->preHandle($request);

echo "<h2>[DEEP SCAN] Searching all configuration data...</h2>";

try {
    $database = \Drupal::database();
    
    // Scan the actual 'data' blob column for the hidden string
    $query = $database->select('config', 'c')
        ->fields('c', ['name', 'data'])
        ->condition('data', '%web_area_menu%', 'LIKE')
        ->execute();
        
    $found = false;
    while ($row = $query->fetchAssoc()) {
        $found = true;
        echo "<p style='color: #d35400;'>Found reference inside configuration object: <strong>" . htmlspecialchars($row['name']) . "</strong></p>";
    }
    
    if (!$found) {
        echo "<p style='color: green;'><strong>No references found in the database config table.</strong></p>";
        echo "<p>This means the rogue plugin string lives exclusively inside a file in your <code>config/sync/</code> folder. Drupal is reading it from the file system during the import phase and crashing.</p>";
    } else {
        echo "<p><em>Once you know the name above, we can target and clean it up.</em></p>";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

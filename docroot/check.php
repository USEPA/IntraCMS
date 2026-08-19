<?php
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// 1. Bootstrap Drupal from the docroot
$autoloader = require_once __DIR__ . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$kernel->preHandle($request);

// 2. Run the Sanity Check
$search_terms = ['web_area_menu', 'group_content_menu'];
$found_configs = [];

try {
    $database = \Drupal::database();
    foreach ($search_terms as $term) {
        $query = $database->select('config', 'c')
            ->fields('c', ['name'])
            ->condition('name', '%' . $database->escapeLike($term) . '%', 'LIKE')
            ->execute();
            
        while ($row = $query->fetchAssoc()) {
            $found_configs[] = $row['name'];
        }
    }

    $found_configs = array_unique($found_configs);

    if (!empty($found_configs)) {
        echo "<h3>[SANITY CHECK] Rogue configuration items found:</h3><ul>";
        foreach ($found_configs as $config_name) {
            echo "<li><strong>" . htmlspecialchars($config_name) . "</strong></li>";
        }
        echo "</ul><p>It is safe to run purge.php now.</p>";
    } else {
        echo "<p style='color: green;'><strong>[SANITY CHECK] Clean! No legacy matching configuration found.</strong></p>";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

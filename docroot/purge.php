<?php
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// 1. Bootstrap Drupal from the docroot
$autoloader = require_once __DIR__ . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$kernel->preHandle($request);

// 2. Execute the Purge
$configs_to_delete = [
    'group.content_by_plugin.web_area_menu',
];
$deleted_count = 0;

try {
    $config_factory = \Drupal::configFactory();
    foreach ($configs_to_delete as $config_name) {
        $config = $config_factory->getEditable($config_name);
        if (!$config->isNew()) {
            $config->delete();
            echo "<p style='color: orange;'>Deleted active config: <strong>" . htmlspecialchars($config_name) . "</strong></p>";
            $deleted_count++;
        }
    }

    if ($deleted_count > 0) {
        echo "<p>Flushing all discovery caches...</p>";
        \Drupal::cache()->invalidateAll();
        drupal_flush_all_caches();
        echo "<p style='color: green;'><strong>[SUCCESS] Clear! Head back to your Config Sync page now.</strong></p>";
    } else {
        echo "<p>No configurations were deleted. Check your spelling or run check.php again.</p>";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

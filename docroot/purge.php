<?php
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// 1. Bootstrap Drupal from the docroot
$autoloader = require_once __DIR__ . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$kernel->preHandle($request);

// 2. Exact configuration names identified by your Deep Scan
$configs_to_delete = [
    'group.content_type.group_content_type_7cdc1b0a6ed7b',
    'block.block.epa_intranet_2_webareamenu',
];
$deleted_count = 0;

try {
    $config_factory = \Drupal::configFactory();
    foreach ($configs_to_delete as $config_name) {
        $config = $config_factory->getEditable($config_name);
        if (!$config->isNew()) {
            $config->delete();
            echo "<p style='color: orange;'>Deleted active database config: <strong>" . htmlspecialchars($config_name) . "</strong></p>";
            $deleted_count++;
        }
    }

    if ($deleted_count > 0) {
        echo "<p>Flushing all discovery caches and invalidating containers...</p>";
        \Drupal::cache()->invalidateAll();
        drupal_flush_all_caches();
        echo "<p style='color: green;'><strong>[SUCCESS] Both rogue objects purged from the DB! Try visiting your Config Sync page now.</strong></p>";
    } else {
        echo "<p>No configurations were deleted. They may have already been removed.</p>";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

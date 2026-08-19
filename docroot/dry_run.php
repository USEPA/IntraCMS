<?php
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

// 1. Bootstrap Drupal from the docroot
$autoloader = require_once __DIR__ . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$kernel->preHandle($request);

// 2. Define the configuration files we suspect are problematic
$configs_to_check = [
    'group.content_by_plugin.web_area_menu',
];

echo "<h2>[DRY RUN] Simulating Configuration Cleanup</h2>";
echo "<p><em>No database changes or deletions are being made. This is a read-only simulation.</em></p><hr>";

try {
    $config_factory = \Drupal::configFactory();
    $found_any = false;

    foreach ($configs_to_check as $config_name) {
        // Load the config as editable to see if it exists in the active database
        $config = $config_factory->getEditable($config_name);
        
        if (!$config->isNew()) {
            $found_any = true;
            echo "<p style='color: #d35400;'>[MATCH FOUND] Configuration object <strong>" . htmlspecialchars($config_name) . "</strong> exists in the database.</p>";
            
            // Output a sneak peek of the data so you can verify what is inside it
            echo "<pre style='background: #f4f4f4; padding: 10px; border: 1px solid #ddd;'>";
            print_r($config->get());
            echo "</pre>";
            
            echo "<p style='color: #27ae60;'>➔ <em>Simulation: Running the purge script would permanently drop this row.</em></p><hr>";
        } else {
            echo "<p style='color: #7f8c8d;'>[NOT FOUND] <strong>" . htmlspecialchars($config_name) . "</strong> is not present in the active database.</p>";
        }
    }

    if ($found_any) {
        echo "<h3>[DRY RUN SUMMARY]</h3>";
        echo "<p>If you execute the final purge script, the items listed above as 'MATCH FOUND' will be removed, and all discovery caches will be flushed to clear the plugin error.</p>";
    } else {
        echo "<h3>[DRY RUN SUMMARY]</h3>";
        echo "<p style='color: #27ae60;'><strong>No targeted configuration found. The issue might be buried inside a different configuration name. Try running check.php to scan the whole database.</strong></p>";
    }

} catch (\Exception $e) {
    echo "<p style='color: red;'>Error during dry run execution: " . $e->getMessage() . "</p>";
}

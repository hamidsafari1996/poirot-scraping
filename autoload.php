<?php

/**
 * Custom autoloader function for the Poirot plugin
 * 
 * This function automatically loads PHP classes in the Poirot namespace.
 * It converts namespace paths to file paths and includes the appropriate files.
 * 
 * @param string $className The fully qualified class name to load
 */
function poirot_autoload($className) {
    // Check if the class belongs to the Poirot namespace
    if (strpos($className, 'Poirot\\') === 0) {
        // Remove the Poirot namespace prefix
        $className = str_replace('Poirot\\', '', $className);
        
        // Convert namespace separators to directory separators
        $className = str_replace('\\', '/', $className);
        
        // Build the full file path
        $file = POIROT_PLUGIN_PATH . 'includes/' . $className . '.php';
        
        // Include the file if it exists
        if (file_exists($file)) {
            require_once $file;
        }
    }
}

// Register the autoloader with PHP
spl_autoload_register('poirot_autoload');
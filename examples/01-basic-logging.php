<?php

/**
 * Example: Basic file logging with level filtering.
 */
require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Log\FileLogger;
use WebFiori\Log\LogLevel;

// Create logger that only records WARNING and above
$logger = new FileLogger(__DIR__.'/logs', LogLevel::WARNING);

$logger->debug('This will NOT be logged (below threshold)');
$logger->info('This will NOT be logged (below threshold)');
$logger->warning('Disk space low', ['free_gb' => 2.1]);
$logger->error('Failed to connect to API', ['url' => 'https://api.example.com', 'code' => 503]);
$logger->critical('Database is down!', ['host' => 'db-primary']);

echo "Log written to: ".__DIR__."/logs/app-".date('Y-m-d').".log\n";
echo file_get_contents(__DIR__.'/logs/app-'.date('Y-m-d').'.log');

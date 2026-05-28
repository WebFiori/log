<?php

/**
 * Example: Creating a custom logger implementation.
 */
require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Log\Logger;
use WebFiori\Log\LoggerFacade;
use WebFiori\Log\LogLevel;

/**
 * A custom logger that stores log entries in memory.
 * Useful for testing or for buffering logs before sending to an external service.
 */
class InMemoryLogger implements Logger {
    private array $entries = [];

    public function debug(string $message, array $context = []): void {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function info(string $message, array $context = []): void {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function warning(string $message, array $context = []): void {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function error(string $message, array $context = []): void {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function critical(string $message, array $context = []): void {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function log(string $level, string $message, array $context = []): void {
        $this->entries[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'time' => time(),
        ];
    }

    public function getEntries(): array {
        return $this->entries;
    }
}

// Use the custom logger with the facade
$logger = new InMemoryLogger();
LoggerFacade::setInstance($logger);

LoggerFacade::info('Order placed', ['order_id' => 101]);
LoggerFacade::error('Payment failed', ['reason' => 'timeout']);

echo "Logged entries:\n";
print_r($logger->getEntries());

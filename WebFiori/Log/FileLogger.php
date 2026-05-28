<?php

/**
 * This file is licensed under MIT License.
 *
 * Copyright (c) 2026 WebFiori Framework
 *
 * For more information on the license, please visit:
 * https://github.com/WebFiori/.github/blob/main/LICENSE
 *
 */
namespace WebFiori\Log;

/**
 * A file-based logger implementation.
 *
 * Writes log entries to daily-rotated files in the specified directory.
 * Format: [YYYY-MM-DD HH:MM:SS] [LEVEL] Message {"key": "value"}
 */
class FileLogger implements Logger {
    /**
     * @var string Path to the log directory.
     */
    private string $logDir;
    /**
     * @var string Minimum level to log.
     */
    private string $minLevel;

    /**
     * Creates a new FileLogger instance.
     *
     * @param string $logDir Path to the directory where log files will be stored.
     * @param string $minLevel Minimum log level to record. Messages below this level are ignored.
     */
    public function __construct(string $logDir, string $minLevel = LogLevel::DEBUG) {
        $this->logDir = rtrim($logDir, DIRECTORY_SEPARATOR);
        $this->minLevel = $minLevel;

        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }
    /**
     * {@inheritDoc}
     */
    public function critical(string $message, array $context = []): void {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }
    /**
     * {@inheritDoc}
     */
    public function debug(string $message, array $context = []): void {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
    /**
     * {@inheritDoc}
     */
    public function error(string $message, array $context = []): void {
        $this->log(LogLevel::ERROR, $message, $context);
    }
    /**
     * Returns the path to the log directory.
     *
     * @return string
     */
    public function getLogDir(): string {
        return $this->logDir;
    }
    /**
     * Returns the minimum log level.
     *
     * @return string
     */
    public function getMinLevel(): string {
        return $this->minLevel;
    }
    /**
     * {@inheritDoc}
     */
    public function info(string $message, array $context = []): void {
        $this->log(LogLevel::INFO, $message, $context);
    }
    /**
     * {@inheritDoc}
     */
    public function log(string $level, string $message, array $context = []): void {
        if (LogLevel::priority($level) < LogLevel::priority($this->minLevel)) {
            return;
        }

        $entry = $this->formatEntry($level, $message, $context);
        file_put_contents($this->getFilePath(), $entry, FILE_APPEND | LOCK_EX);
    }
    /**
     * Sets the minimum log level.
     *
     * @param string $level One of the LogLevel constants.
     */
    public function setMinLevel(string $level): void {
        $this->minLevel = $level;
    }
    /**
     * {@inheritDoc}
     */
    public function warning(string $message, array $context = []): void {
        $this->log(LogLevel::WARNING, $message, $context);
    }
    /**
     * Formats a log entry as a string.
     *
     * @param string $level The log level.
     * @param string $message The log message.
     * @param array $context Contextual data.
     *
     * @return string Formatted log line.
     */
    private function formatEntry(string $level, string $message, array $context): string {
        $timestamp = date('Y-m-d H:i:s');
        $levelUpper = strtoupper($level);
        $line = "[$timestamp] [$levelUpper] $message";

        if (!empty($context)) {
            $line .= ' '.json_encode($context, JSON_UNESCAPED_SLASHES);
        }

        return $line."\n";
    }
    /**
     * Returns the file path for today's log file.
     *
     * @return string
     */
    private function getFilePath(): string {
        return $this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log';
    }
}

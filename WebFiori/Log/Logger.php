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
 * Interface for logging implementations.
 *
 * Defines standard log levels and a generic log method.
 */
interface Logger {
    /**
     * Log a critical message.
     *
     * @param string $message The log message.
     * @param array $context Key-value pairs of contextual data.
     */
    public function critical(string $message, array $context = []): void;
    /**
     * Log a debug message.
     *
     * @param string $message The log message.
     * @param array $context Key-value pairs of contextual data.
     */
    public function debug(string $message, array $context = []): void;
    /**
     * Log an error message.
     *
     * @param string $message The log message.
     * @param array $context Key-value pairs of contextual data.
     */
    public function error(string $message, array $context = []): void;
    /**
     * Log an informational message.
     *
     * @param string $message The log message.
     * @param array $context Key-value pairs of contextual data.
     */
    public function info(string $message, array $context = []): void;
    /**
     * Log a message at the given level.
     *
     * @param string $level One of the LogLevel constants.
     * @param string $message The log message.
     * @param array $context Key-value pairs of contextual data.
     */
    public function log(string $level, string $message, array $context = []): void;
    /**
     * Log a warning message.
     *
     * @param string $message The log message.
     * @param array $context Key-value pairs of contextual data.
     */
    public function warning(string $message, array $context = []): void;
}

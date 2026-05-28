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
 * Constants for log levels.
 */
class LogLevel {
    const CRITICAL = 'critical';
    const DEBUG = 'debug';
    const ERROR = 'error';
    const INFO = 'info';
    const WARNING = 'warning';

    /**
     * Returns all levels in order of severity (lowest to highest).
     *
     * @return array
     */
    public static function all(): array {
        return [
            self::DEBUG,
            self::INFO,
            self::WARNING,
            self::ERROR,
            self::CRITICAL,
        ];
    }
    /**
     * Returns the numeric priority of a level (higher = more severe).
     *
     * @param string $level One of the LogLevel constants.
     *
     * @return int Priority value (0-4).
     */
    public static function priority(string $level): int {
        return array_search($level, self::all(), true) ?: 0;
    }
}

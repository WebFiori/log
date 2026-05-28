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
 * A static facade for the Logger.
 *
 * Provides a convenient static API that delegates to a default Logger instance.
 * For dependency injection or multiple loggers, use the Logger interface directly.
 */
class LoggerFacade {
    /**
     * @var Logger|null The default logger instance.
     */
    private static ?Logger $inst = null;
    /**
     * @see Logger::critical()
     */
    public static function critical(string $message, array $context = []): void {
        self::getInstance()->critical($message, $context);
    }
    /**
     * @see Logger::debug()
     */
    public static function debug(string $message, array $context = []): void {
        self::getInstance()->debug($message, $context);
    }
    /**
     * @see Logger::error()
     */
    public static function error(string $message, array $context = []): void {
        self::getInstance()->error($message, $context);
    }
    /**
     * Returns the default Logger instance, creating a FileLogger lazily if needed.
     *
     * @return Logger
     */
    public static function getInstance(): Logger {
        if (self::$inst === null) {
            self::$inst = new FileLogger(
                sys_get_temp_dir().DIRECTORY_SEPARATOR.'webfiori-logs'
            );
        }

        return self::$inst;
    }
    /**
     * @see Logger::info()
     */
    public static function info(string $message, array $context = []): void {
        self::getInstance()->info($message, $context);
    }
    /**
     * @see Logger::log()
     */
    public static function log(string $level, string $message, array $context = []): void {
        self::getInstance()->log($level, $message, $context);
    }
    /**
     * Destroys the default Logger instance. The next call will create a fresh one.
     */
    public static function reset(): void {
        self::$inst = null;
    }
    /**
     * Replaces the default Logger instance.
     *
     * @param Logger $logger The logger instance to use as default.
     */
    public static function setInstance(Logger $logger): void {
        self::$inst = $logger;
    }
    /**
     * @see Logger::warning()
     */
    public static function warning(string $message, array $context = []): void {
        self::getInstance()->warning($message, $context);
    }
}

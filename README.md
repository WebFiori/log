# WebFiori Log

A lightweight structured logging library for PHP with daily file rotation and level filtering.

<p align="center">
  <a href="https://github.com/WebFiori/log/actions"><img src="https://github.com/WebFiori/log/actions/workflows/php84.yaml/badge.svg?branch=main"></a>
  <a href="https://codecov.io/gh/WebFiori/log">
    <img src="https://codecov.io/gh/WebFiori/log/branch/main/graph/badge.svg" />
  </a>
  <a href="https://sonarcloud.io/dashboard?id=WebFiori_log">
      <img src="https://sonarcloud.io/api/project_badges/measure?project=WebFiori_log&metric=alert_status" />
  </a>
  <a href="https://github.com/WebFiori/log/releases">
      <img src="https://img.shields.io/github/release/WebFiori/log.svg?label=latest" />
  </a>
  <a href="https://packagist.org/packages/webfiori/log">
      <img src="https://img.shields.io/packagist/dt/webfiori/log?color=light-green">
  </a>
</p>

## Supported PHP Versions

This library requires **PHP 8.1 or higher**.

|                                                                                        Build Status                                                                                        |
|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| <a target="_blank" href="https://github.com/WebFiori/log/actions/workflows/php81.yaml"><img src="https://github.com/WebFiori/log/actions/workflows/php81.yaml/badge.svg?branch=main"></a>  |
| <a target="_blank" href="https://github.com/WebFiori/log/actions/workflows/php82.yaml"><img src="https://github.com/WebFiori/log/actions/workflows/php82.yaml/badge.svg?branch=main"></a>  |
| <a target="_blank" href="https://github.com/WebFiori/log/actions/workflows/php83.yaml"><img src="https://github.com/WebFiori/log/actions/workflows/php83.yaml/badge.svg?branch=main"></a>  |
| <a target="_blank" href="https://github.com/WebFiori/log/actions/workflows/php84.yaml"><img src="https://github.com/WebFiori/log/actions/workflows/php84.yaml/badge.svg?branch=main"></a>  |
| <a target="_blank" href="https://github.com/WebFiori/log/actions/workflows/php85.yaml"><img src="https://github.com/WebFiori/log/actions/workflows/php85.yaml/badge.svg?branch=main"></a>  |

## Features

- **Logger interface** — swap implementations without changing application code
- **FileLogger** — daily-rotated log files with structured format
- **Static facade** (`LoggerFacade`) for quick usage without DI
- **Level filtering** — set minimum level (debug, info, warning, error, critical)
- **Structured context** — attach key-value metadata to log entries
- **Zero dependencies** — requires only PHP 8.1+

## Installation

```bash
composer require webfiori/log
```

## Usage

### Basic Logging

```php
use WebFiori\Log\FileLogger;
use WebFiori\Log\LogLevel;

$logger = new FileLogger('/var/log/myapp', LogLevel::INFO);

$logger->info('User logged in', ['user_id' => 42, 'ip' => '192.168.1.1']);
$logger->error('Payment failed', ['order_id' => 123, 'reason' => 'timeout']);
$logger->debug('This will be filtered out'); // below INFO threshold
```

**Output** (`/var/log/myapp/app-2026-05-29.log`):
```
[2026-05-29 01:00:00] [INFO] User logged in {"user_id":42,"ip":"192.168.1.1"}
[2026-05-29 01:00:00] [ERROR] Payment failed {"order_id":123,"reason":"timeout"}
```

### Static Facade

```php
use WebFiori\Log\LoggerFacade;

LoggerFacade::info('Application started');
LoggerFacade::error('Something went wrong', ['exception' => $e->getMessage()]);
```

### Custom Logger Implementation

```php
use WebFiori\Log\Logger;

class DatabaseLogger implements Logger {
    public function debug(string $message, array $context = []): void { /* ... */ }
    public function info(string $message, array $context = []): void { /* ... */ }
    public function warning(string $message, array $context = []): void { /* ... */ }
    public function error(string $message, array $context = []): void { /* ... */ }
    public function critical(string $message, array $context = []): void { /* ... */ }
    public function log(string $level, string $message, array $context = []): void { /* ... */ }
}

LoggerFacade::setInstance(new DatabaseLogger());
```

## API

### `Logger` (interface)

| Method | Description |
|--------|-------------|
| `debug(string $message, array $context = [])` | Log debug message |
| `info(string $message, array $context = [])` | Log informational message |
| `warning(string $message, array $context = [])` | Log warning message |
| `error(string $message, array $context = [])` | Log error message |
| `critical(string $message, array $context = [])` | Log critical message |
| `log(string $level, string $message, array $context = [])` | Log at specified level |

### `FileLogger`

| Method | Description |
|--------|-------------|
| `__construct(string $logDir, string $minLevel = 'debug')` | Create logger with directory and minimum level |
| `getLogDir(): string` | Returns the log directory path |
| `getMinLevel(): string` | Returns the current minimum level |
| `setMinLevel(string $level): void` | Change the minimum level at runtime |

### `LogLevel`

| Constant | Value |
|----------|-------|
| `LogLevel::DEBUG` | `'debug'` |
| `LogLevel::INFO` | `'info'` |
| `LogLevel::WARNING` | `'warning'` |
| `LogLevel::ERROR` | `'error'` |
| `LogLevel::CRITICAL` | `'critical'` |

### `LoggerFacade`

| Method | Description |
|--------|-------------|
| `getInstance(): Logger` | Get the default logger |
| `setInstance(Logger $logger): void` | Replace the default logger |
| `reset(): void` | Destroy the default instance |
| All `Logger` methods | Delegates to the default instance |

## License

MIT

<?php

namespace WebFiori\Log\Tests;

use PHPUnit\Framework\TestCase;
use WebFiori\Log\FileLogger;
use WebFiori\Log\Logger;
use WebFiori\Log\LoggerFacade;
use WebFiori\Log\LogLevel;

class LoggerFacadeTest extends TestCase {
    protected function setUp(): void {
        LoggerFacade::reset();
    }
    /**
     * @test
     */
    public function testGetInstanceReturnsLogger() {
        $this->assertInstanceOf(Logger::class, LoggerFacade::getInstance());
    }
    /**
     * @test
     */
    public function testSetInstance() {
        $logDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'wf_facade_test_'.getmypid();
        $custom = new FileLogger($logDir, LogLevel::ERROR);
        LoggerFacade::setInstance($custom);
        $this->assertSame($custom, LoggerFacade::getInstance());

        // Cleanup
        if (is_dir($logDir)) {
            rmdir($logDir);
        }
    }
    /**
     * @test
     */
    public function testResetCreatesNewInstance() {
        $first = LoggerFacade::getInstance();
        LoggerFacade::reset();
        $second = LoggerFacade::getInstance();
        $this->assertNotSame($first, $second);
    }
    /**
     * @test
     */
    public function testFacadeDelegatesLog() {
        $logDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'wf_facade_log_'.getmypid();
        LoggerFacade::setInstance(new FileLogger($logDir));

        LoggerFacade::info('Facade test');
        LoggerFacade::error('Error test', ['code' => 500]);
        LoggerFacade::debug('Debug test');
        LoggerFacade::warning('Warn test');
        LoggerFacade::critical('Crit test');
        LoggerFacade::log(LogLevel::INFO, 'Generic test');

        $content = file_get_contents($logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringContainsString('[INFO] Facade test', $content);
        $this->assertStringContainsString('[ERROR] Error test', $content);
        $this->assertStringContainsString('[DEBUG] Debug test', $content);
        $this->assertStringContainsString('[WARNING] Warn test', $content);
        $this->assertStringContainsString('[CRITICAL] Crit test', $content);
        $this->assertStringContainsString('[INFO] Generic test', $content);

        // Cleanup
        array_map('unlink', glob($logDir.DIRECTORY_SEPARATOR.'*'));
        rmdir($logDir);
    }
}

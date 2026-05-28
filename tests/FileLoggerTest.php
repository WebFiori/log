<?php

namespace WebFiori\Log\Tests;

use PHPUnit\Framework\TestCase;
use WebFiori\Log\FileLogger;
use WebFiori\Log\LogLevel;

class FileLoggerTest extends TestCase {
    private string $logDir;

    protected function setUp(): void {
        $this->logDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'wf_log_test_'.getmypid();
    }

    protected function tearDown(): void {
        if (is_dir($this->logDir)) {
            $files = glob($this->logDir.DIRECTORY_SEPARATOR.'*');

            foreach ($files as $file) {
                unlink($file);
            }
            rmdir($this->logDir);
        }
    }
    /**
     * @test
     */
    public function testCreatesLogDirectory() {
        $logger = new FileLogger($this->logDir);
        $this->assertTrue(is_dir($this->logDir));
    }
    /**
     * @test
     */
    public function testDebugWritesToFile() {
        $logger = new FileLogger($this->logDir);
        $logger->debug('Test message');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringContainsString('[DEBUG] Test message', $content);
    }
    /**
     * @test
     */
    public function testInfoWritesToFile() {
        $logger = new FileLogger($this->logDir);
        $logger->info('Info message');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringContainsString('[INFO] Info message', $content);
    }
    /**
     * @test
     */
    public function testWarningWritesToFile() {
        $logger = new FileLogger($this->logDir);
        $logger->warning('Warning message');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringContainsString('[WARNING] Warning message', $content);
    }
    /**
     * @test
     */
    public function testErrorWritesToFile() {
        $logger = new FileLogger($this->logDir);
        $logger->error('Error message');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringContainsString('[ERROR] Error message', $content);
    }
    /**
     * @test
     */
    public function testCriticalWritesToFile() {
        $logger = new FileLogger($this->logDir);
        $logger->critical('Critical message');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringContainsString('[CRITICAL] Critical message', $content);
    }
    /**
     * @test
     */
    public function testContextIsJsonEncoded() {
        $logger = new FileLogger($this->logDir);
        $logger->info('User login', ['user_id' => 42, 'ip' => '192.168.1.1']);

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringContainsString('{"user_id":42,"ip":"192.168.1.1"}', $content);
    }
    /**
     * @test
     */
    public function testEmptyContextNotAppended() {
        $logger = new FileLogger($this->logDir);
        $logger->info('Simple message');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringNotContainsString('{', $content);
    }
    /**
     * @test
     */
    public function testMinLevelFilters() {
        $logger = new FileLogger($this->logDir, LogLevel::WARNING);
        $logger->debug('Should not appear');
        $logger->info('Should not appear');
        $logger->warning('Should appear');

        $file = $this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log';
        $content = file_get_contents($file);
        $this->assertStringNotContainsString('Should not appear', $content);
        $this->assertStringContainsString('Should appear', $content);
    }
    /**
     * @test
     */
    public function testSetMinLevel() {
        $logger = new FileLogger($this->logDir, LogLevel::DEBUG);
        $logger->setMinLevel(LogLevel::ERROR);
        $logger->warning('Filtered out');
        $logger->error('Passes through');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringNotContainsString('Filtered out', $content);
        $this->assertStringContainsString('Passes through', $content);
    }
    /**
     * @test
     */
    public function testGetLogDir() {
        $logger = new FileLogger($this->logDir);
        $this->assertEquals($this->logDir, $logger->getLogDir());
    }
    /**
     * @test
     */
    public function testGetMinLevel() {
        $logger = new FileLogger($this->logDir, LogLevel::ERROR);
        $this->assertEquals(LogLevel::ERROR, $logger->getMinLevel());
    }
    /**
     * @test
     */
    public function testMultipleEntriesAppend() {
        $logger = new FileLogger($this->logDir);
        $logger->info('First');
        $logger->info('Second');
        $logger->info('Third');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringContainsString('First', $content);
        $this->assertStringContainsString('Second', $content);
        $this->assertStringContainsString('Third', $content);
    }
    /**
     * @test
     */
    public function testTimestampFormat() {
        $logger = new FileLogger($this->logDir);
        $logger->info('Timestamp test');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertMatchesRegularExpression('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/', $content);
    }
    /**
     * @test
     */
    public function testLogMethodDirectly() {
        $logger = new FileLogger($this->logDir);
        $logger->log(LogLevel::INFO, 'Direct log call');

        $content = file_get_contents($this->logDir.DIRECTORY_SEPARATOR.'app-'.date('Y-m-d').'.log');
        $this->assertStringContainsString('[INFO] Direct log call', $content);
    }
}

<?php

namespace WebFiori\Log\Tests;

use PHPUnit\Framework\TestCase;
use WebFiori\Log\LogLevel;

class LogLevelTest extends TestCase {
    /**
     * @test
     */
    public function testAllReturnsLevelsInOrder() {
        $levels = LogLevel::all();
        $this->assertEquals(['debug', 'info', 'warning', 'error', 'critical'], $levels);
    }
    /**
     * @test
     */
    public function testPriority() {
        $this->assertEquals(0, LogLevel::priority(LogLevel::DEBUG));
        $this->assertEquals(1, LogLevel::priority(LogLevel::INFO));
        $this->assertEquals(2, LogLevel::priority(LogLevel::WARNING));
        $this->assertEquals(3, LogLevel::priority(LogLevel::ERROR));
        $this->assertEquals(4, LogLevel::priority(LogLevel::CRITICAL));
    }
    /**
     * @test
     */
    public function testUnknownLevelReturnsZero() {
        $this->assertEquals(0, LogLevel::priority('unknown'));
    }
}

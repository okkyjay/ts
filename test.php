<?php
use PHPUnit\Framework\TestCase;

class Calculator
{
    public function add($a, $b)
    {
        return $a + $b;
    }

    public function subtract($a, $b)
    {
        return $a - $b;
    }
}


class CalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }

    protected function tearDown(): void
    {
        $this->calculator = null;
    }

    public function testAdd()
    {
        $result = $this->calculator->add(3, 5);
        $this->assertEquals(8, $result);
    }

    public function testSubtract()
    {
        $result = $this->calculator->subtract(10, 4);
        $this->assertEquals(6, $result);
    }
}

// CalculatorTest.php (updated)

class CalculatorUpdatedTest extends TestCase
{
    public function testAdd()
    {
        // Create a mock for the Logger class
        $loggerMock = $this->getMockBuilder(Logger::class)
            ->onlyMethods(['log'])
            ->getMock();

        // Expect the log method to be called once with the specified message
        $loggerMock->expects($this->once())
            ->method('log')
            ->with("Adding 3 and 5");

        // Instantiate the Calculator class with the mock logger
        $calculator = new Calculator($loggerMock);

        // Call the add method
        $result = $calculator->add(3, 5);

        // Assert the result
        $this->assertEquals(8, $result);
    }

    // Similar test for the subtract method
}

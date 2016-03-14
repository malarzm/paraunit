<?php

namespace Paraunit\Output\TestResult;

/**
 * Class MuteTestResult
 * @package Paraunit\Output\MuteTestResult
 */
class MuteTestResult implements TestResultInterface
{
    /** @var string */
    private $testResultSymbol;

    /**
     * MuteTestResult constructor.
     * @param string $testResultSymbol
     */
    public function __construct($testResultSymbol)
    {
        $this->testResultSymbol = $testResultSymbol;
    }

    /**
     * @return string
     */
    public function getTestResultSymbol()
    {
        return $this->testResultSymbol;
    }
}

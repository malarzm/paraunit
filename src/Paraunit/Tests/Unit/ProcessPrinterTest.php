<?php

namespace Paraunit\Tests\Unit;

use Paraunit\Lifecycle\ProcessEvent;
use Paraunit\Printer\ProcessPrinter;
use Paraunit\Tests\Stub\ConsoleOutputStub;
use Paraunit\Tests\Stub\StubbedParaProcess;
use Prophecy\Argument;

class ProcessPrinterTest extends \PHPUnit_Framework_TestCase
{
    public function testPrintProcessResultWithRetry()
    {
        $process = new StubbedParaProcess();
        $process->setIsToBeRetried(true);

        $printer = new ProcessPrinter();
        $output = new ConsoleOutputStub();

        $processEvent = new ProcessEvent($process, array('output_interface' => $output));
        $printer->onProcessTerminated($processEvent);

        $this->assertEquals('<ok>A</ok>', $output->getOutput());
    }

    public function testPrintProcessResultWithAbnormalTermination()
    {
        $process = new StubbedParaProcess();
        $process->reportAbnormalTerminationInFunction('die()');

        $printer = new ProcessPrinter();
        $output = new ConsoleOutputStub();

        $processEvent = new ProcessEvent($process, array('output_interface' => $output));
        $printer->onProcessTerminated($processEvent);

        $this->assertEquals('<halted>X</halted>', $output->getOutput());
    }

    /**
     * @dataProvider newLineTimesProvider
     */
    public function testPrintProcessResult_new_line_after_80_chars($times, $newLineTimes)
    {
        $process = new StubbedParaProcess();
        $process->setTestResults(array_fill(0, $times, 'F'));

        $printer = new ProcessPrinter();
        $output = $this->prophesize('Paraunit\Tests\Stub\ConsoleOutputStub');
        $output->write('<fail>F</fail>')->willReturn()->shouldBeCalledTimes($times);
        $output->writeln('')->willReturn()->shouldBeCalledTimes($newLineTimes);

        $processEvent = new ProcessEvent($process, array('output_interface' => $output->reveal()));
        $printer->onProcessTerminated($processEvent);
    }

    public function newLineTimesProvider()
    {
        return array(
            array(79, 0),
            array(80, 0),
            array(81, 1),
            array(200, 2),
            array(240, 2),
            array(241, 3),
        );
    }

    /**
     * @dataProvider testResultProvider
     */
    public function testPrintProcessResult_proper_output_with_normal_testresults($testResult, $expectedOutput)
    {
        $process = new StubbedParaProcess();
        $process->setTestResults(array($testResult));

        $printer = new ProcessPrinter();
        $output = new ConsoleOutputStub();

        $processEvent = new ProcessEvent($process, array('output_interface' => $output));
        $printer->onProcessTerminated($processEvent);

        $this->assertEquals($expectedOutput, $output->getOutput());
    }

    public function testResultProvider()
    {
        return array(
            array('F', '<fail>F</fail>'),
            array('E', '<error>E</error>'),
            array('I', '<incomplete>I</incomplete>'),
            array('S', '<skipped>S</skipped>'),
            array('R', '<risky>R</risky>'),
            array('W', '<warning>W</warning>'),
            array(null, '<warning>?</warning>'),
        );
    }
}

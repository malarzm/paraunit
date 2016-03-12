<?php

namespace Paraunit\Printer;

use Paraunit\Process\ProcessResultInterface;

/**
 * Class NullOutputContainer
 * @package Paraunit\Printer
 */
class NullOutputContainer implements OutputContainerInterface
{
    public function addToOutputBuffer(ProcessResultInterface $process, $message)
    {
    }

    public function getFileNames()
    {
        return array();
    }

    public function getOutputBuffer()
    {
        return array();
    }

    public function countFiles()
    {
        return 0;
    }

    public function getTag()
    {
        return 'null';
    }

    public function getTitle()
    {
        return '---';
    }
}

<?php
/**
 * Stub file to allow calls to output that don't throw errors
 *
 * @package Cubic Mushroom Symfony Extension
 *
 * @author    Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright 2012 Cubic Mushroom Ltd
 *
 * @license MIT
 */

namespace CubicMushroom\Symfony\Component\Console\Output;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 
 */
class OutputStub implements OutputInterface
{
    public function write($messages, $newline = false, $type = 0) {}
    public function writeln($messages, $type = 0) {}
    public function setVerbosity($level) {}
    public function getVerbosity() {}
    public function setDecorated($decorated) {}
    public function isDecorated() {}
    public function setFormatter(OutputFormatterInterface $formatter) {}
    public function getFormatter() {}
}
<?php
/**
 * Command to clone a remote WordPress environment to a local environment
 *
 * @package    Cubic Mushroom WordPress Tools
 * @subpackage Console Commands
 *
 * @author    Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright 2012 Cubic Mushroom Ltd
 *
 * @license MIT
 */

namespace CubicMushroom\WordPress\FileManagerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to clone a remote WordPress environment to a local environment
 *
 * @package    Cubic Mushroom WordPress Tools
 * @subpackage Console Commands
 *
 * @author    Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright 2012 Cubic Mushroom Ltd
 *
 * @license MIT
 */
class CloneRemoteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wordpress:clone:remote')
            ->setDescription('Clones a remote WordPress site to the local machine');
    }

    protected  function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<error>Command not finished</error>');
    }
}
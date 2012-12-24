<?php
/**
 * Command that sets up a local WordPress environment
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
 * Command that sets up a local WordPress environment
 *
 * @package    Cubic Mushroom WordPress Tools
 * @subpackage Console Commands
 *
 * @author    Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright 2012 Cubic Mushroom Ltd
 *
 * @license MIT
 */
class SetupLocalCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wordpress:setup:local')
            ->setDescription('Sets up a local WordPress environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileManager = $this->getContainer()->get('filemanager');

        try {
            $fileManager->downloadWordPress();
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }


    }
}
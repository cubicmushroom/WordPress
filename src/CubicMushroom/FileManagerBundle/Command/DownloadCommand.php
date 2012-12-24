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

namespace CubicMushroom\FileManagerBundle\Command;

use CubicMushroom\FileManagerBundle\Exception\UnknowWordPressVersionException;
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
class DownloadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wordpress:download:version')
            ->setDescription('Downloads a version of WordPress to the tmp folder')
            ->addArgument(
                'version',      // Can't use 'version' as I beleive it's a reserved word
                InputArgument::OPTIONAL,
                'What version to download.  Leave blank to download the latest version',
                'latest'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileManager = $this->getContainer()->get('filemanager');

        $version = $input->getArgument('version');

        try {
            $fileManager->downloadWordPress($version);
        } catch (\CubicMushroom\FileManagerBundle\Exception\UnknowWordPressVersionException $e) {
            $output->writeln('');
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            $output->writeln(
                "<error>Recent versions available include " . 
                implode(', ', $e->getRecentVersions()) .
                '</error>'
            );
            $output->writeln('');
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }


    }
}
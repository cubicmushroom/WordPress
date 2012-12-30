<?php
/**
 * File to handle actual management of files, including upload & download
 *
 * @package Cubic Mushroom WordPress Tools
 *
 * @author    Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright 2012 Cubic Mushroom Ltd
 *
 * @license MIT
 */

namespace CubicMushroom\WordPress\FileManagerBundle\Component;

use CubicMushroom\FileHelper\FileHelper;
use CubicMushroom\Symfony\Component\Console\Output\OutputStub;
use CubicMushroom\WordPress\FileManagerBundle\Exception;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Process;
use Webcreate\Vcs\Svn;
use Webcreate\Vcs\Common\Pointer;

/**
 * Handles management of files, including upload & download
 *
 * @package Cubic Mushroom WordPress Tools
 *
 * @author    Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright 2012 Cubic Mushroom Ltd
 *
 * @license MIT
 */
class FileManager {

    /**
     * @var string Application root
     */
    protected $appRoot;

    /**
     * @var string Directory to download the files to
     */
    protected $downloadDir;

    /**
     * @var Stores WordPress repository access object
     */
    protected $vcs;

    /**
     * @var object Output Interface object used to output during console commands
     */
    protected $output;

    /**
     * Stores the service container to $this->container
     *
     * @param ContainerInterface $contains Dependency Injection Service Container
     */
    public function __construct($appRoot, $tmpDir)
    {
        $this->appRoot = $appRoot;
        $this->downloadDir = $tmpDir;
        $this->output = new OutputStub();
    }

    /*********************
     * Getters & Setters *
     *********************/

    /**
     * Returns the Version Control access object, creating it first if necessary
     *
     * @return object
     */
    protected function getVcs()
    {
        if (empty($this->vcs)) {
            $this->vcs = new Svn('http://core.svn.wordpress.org');
        }
        return $this->vcs;
    }

    /**
     * Sets the download directory
     *
     * @param string $dir Directory 
     */
    public function setDownloadDir($dir)
    {
        $this->downloadDir = $dir;
    }

    /**
     * Allows the setting of an output object to be used when outputting to console
     *
     * @param OutputInterface $output Output interface object
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /******************
     * Action methods *
     ******************/

    /**
     * Downloads a copy of WordPress from the Subversion repository
     *
     * @param string $version The version number or WordPress to download.  If not
     *                        given, defaults to latest version
     *
     * @return string Returns the full path to the download file
     */
    public function downloadWordPress($version = null)
    {
        if (empty($version) || 'latest' == $version) {
            $this->output->writeln(
                "<info>Attempting to download the latest version of WordPress</info>"
            );
        } else {
            $this->output->writeln(
                "<info>Attempting to download WordPress version $version"
            );
            $wpArchive = $this->getArchiveFile($version);

            if ($this->checkArchiveExists($version)) {
                return $this->getArchiveFile($version);
            }
        }

        $this->output->writeln('<info>Fetching WordPress versions</info>');

        $vcs = $this->getVcs();

        // Get SVN tags
        $tags = $vcs->tags();

        if (empty($version) || 'latest' == $version) {
            rsort($tags);
            $version = $tags[0];
            $this->output->writeln(
                "<info>Latest version is $version</info>"
            );

            if ($this->checkArchiveExists($version)) {
                return $this->getArchiveFile($version);
            }

            $wpArchive = $this->getArchiveFile($version);
        }
echo $wpArchive; exit;

        if (!in_array($version, $tags)) {
            throw new Exception\UnknowWordPressVersionException(
                "$version is not a valid WordPress version",
                $tags
            );
        }

        $this->output->writeln("<info>Beginning download</info>");

        // Switch to the relevant version tag
        $vcs->setPointer(new Pointer($version, Pointer::TYPE_TAG));

        // Download the files
        $downloadDir = preg_replace('/\.tar\.gz$/', '', $wpArchive);
        $vcs->export("/", $downloadDir);

        // Now combine into an archive & compress
        $tarProcess = new Process("tar -czf $wpArchive *", $downloadDir);
        $tarProcess->setTimeout(3600);
        $tarProcess->run();
        if (!$tarProcess->isSuccessful()) {
            throw new \RuntimeException($tarProcess->getErrorOutput());
        }

        $fileHelper = new FileHelper();

        $fileHelper->rmdir($downloadDir);

        $this->output->writeln(
            "<info>WordPress version $version downloaded to $wpArchive</info>"
        );

        return $this->getArchiveFile($version);
    }

    /**
     * Calculates the WP archive file to use
     *
     * @param string $version Version number
     *
     * @return string Full path & filename string
     */
    protected function getArchiveFile($version)
    {
        // Get temp folder
        $tmpDir = $this->downloadDir;
        if ('/' != substr($this->downloadDir, 0, 1)) {
            $tmpDir = dirname($this->appRoot) . "/$tmpDir";
        }

        return "$tmpDir/wordpress-$version.tar.gz";
    }

    /**
     * Checks if archive is already downloaded & reports to $this->output, if so
     *
     * @param string $version Version number to check for
     *
     * @return boolean
     */
    protected function checkArchiveExists($version)
    {
        $wpArchive = $this->getArchiveFile($version);
        if (file_exists($wpArchive)) {
            $this->output->writeln(
                "<info>Version $version already downloaded</info>"
            );
            $this->output->writeln("<info>File can be found at $wpArchive</info>");
            return $wpArchive;
        }
    }
}
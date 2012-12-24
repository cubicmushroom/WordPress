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
use CubicMushroom\WordPress\FileManagerBundle\Exception;
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
     * Reference to 
     * @var ContainerInterface object
     */
    protected $container;

    /**
     * Stores the service container to $this->container
     *
     * @param ContainerInterface $contains Dependency Injection Service Container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Downloads a copy of WordPress from the Subversion repository
     *
     * @param string $version The version number or WordPress to download.  If not
     *                        given, defaults to latest version
     */
    public function downloadWordPress($version = null)
    {

        $svn = new Svn('http://core.svn.wordpress.org');

        // Get SVN tags
        $tags = $svn->tags();

        if (empty($version) || 'latest' == $version) {
            rsort($tags);
            $version = $tags[0];
        }

        if (!in_array($version, $tags)) {
            throw new Exception\UnknowWordPressVersionException(
                "$version is not a valid WordPress version",
                $tags
            );
        }

        // Get temp folder
        $tmpDir = $this->container->getParameter('tmp_folder');
        if (empty($tmpDir)) {
            $tmpDir = 'tmp';
        }
        if ('/' != substr($tmpDir, 0, 1)) {
            $tmpDir = dirname($this->container->get('kernel')->getRootDir()) . "/$tmpDir";
        }

        $wpArchive = "$tmpDir/wordpress/wordpress-$version.tar.gz";
echo "wpArchive: $wpArchive\n";
        $downloadDir = preg_replace('/\.tar\.gz$/', '', $wpArchive);
echo "downloadDir: $downloadDir\n";

        // Check if version is already downloaded
        if (file_exists($wpArchive)) {
            return $wpArchive;
        }

        // Switch to the relevant version tag
        $svn->setPointer(new Pointer($version, Pointer::TYPE_TAG));

        // Download the files
        $svn->export("/", $downloadDir);

        // Now combine into an archive & compress
        $tarProcess = new Process("tar -czf $wpArchive *", $downloadDir);
        $tarProcess->setTimeout(3600);
        $tarProcess->run();
        if (!$tarProcess->isSuccessful()) {
            throw new \RuntimeException($tarProcess->getErrorOutput());
        }

        $fileHelper = new FileHelper();

        $fileHelper->rmdir($downloadDir);
        echo "Created $wpArchive archive"; exit;

        // And delete the downloaded files
    }
}
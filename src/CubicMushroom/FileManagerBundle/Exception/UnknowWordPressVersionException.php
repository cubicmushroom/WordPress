<?php
/**
 * Exception thrown when attempting to download a non-existent version of Wordpress
 *
 * @package    Cubic Mushroom WordPress Tools
 * @subpackage Console Commands
 *
 * @author    Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright 2012 Cubic Mushroom Ltd
 *
 * @license MIT
 */

namespace CubicMushroom\FileManagerBundle\Exception;

/**
 * Exception thrown when attempting to download a non-existent version of Wordpress
 *
 * @package    Cubic Mushroom WordPress Tools
 * @subpackage Console Commands
 *
 * @author    Toby Griffiths <toby@cubicmushroom.co.uk>
 * @copyright 2012 Cubic Mushroom Ltd
 *
 * @license MIT
 */
class UnknowWordPressVersionException extends \Exception
{
    /**
     * @var array Versions currently available
     */
    protected $availableVersions;

    /**
     * 
     *
     * @param array $versions Array of all the available version numbers
     *
     * @return void
     */
    public function __construct($msg, $versions)
    {
        rsort($versions);
        $this->availableVersions = $versions;
        parent::__construct($msg);
    }

    /**
     * Returns the most versions of WordPress available
     *
     * @param integer $count Number of versions to include
     *
     * @return array Returns an array of the most recent $count versions
     */
    public function getRecentVersions($count = 10)
    {
        return array_slice($this->availableVersions, 0, $count);
    }
}
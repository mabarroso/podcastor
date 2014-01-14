<?php
/**
 * Download.php
 *
 * PHP version 5.2
 *
 * @category  PodcastCollector
 * @package   Download
 * @author    mabarroso <mabarroso@mabarroso.com>
 * @copyright 2014 mabarroso.com
 * @license   Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version   GIT: $Id$
 * @link      http://www.mabarroso.com
 * @since     File available since Release 0.1
 */

/**
 * Download
 *
 * @category  PodcastCollector
 * @package   Download
 * @author    mabarroso <mabarroso@mabarroso.com>
 * @copyright 2014 mabarroso.com
 * @license   Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version   GIT: $Id$
 * @link      http://www.mabarroso.com
 * @since     File available since Release 0.1
 */
class Download
{
    public $path;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->path = false;
    }


    /**
     * [setPath description]
     *
     * @param [type] $path [description]
     *
     * @return none
     */
    public function setPath($path)
    {
        $this->path = $path;
        if (!file_exists($this->path)) mkdir($this->path, 0777, true);
    }

    /**
     * [getPendName description]
     *
     * @param [type] $filename [description]
     *
     * @return none
     */
    public function getPendName($filename)
    {
        return $this->path.'/'.$filename.'.pend';
    }

    /**
     * [getMp3Name description]
     *
     * @param [type] $filename [description]
     *
     * @return none
     */
    public function getMp3Name($filename)
    {
        return $this->path.'/'.$filename.'.mp3';
    }

    /**
     * [add description]
     *
     * @param [type] $name [description]
     * @param [type] $url  [description]
     *
     * @return none
     */
    public function add($name, $url)
    {
        if (!$this->path) return;

        $filename_pend = $this->getPendName($name);
        $filename_mp3 = $this->getMp3Name($name);

        if (!file_exists($filename_pend) && !file_exists($filename_mp3)) {
            file_put_contents($filename_pend, $url);
        }
    }

    public function getURL($filename, $pathToRemove, $toAdd)
    {
        $filename_mp3 = $this->getMp3Name($filename);

        return str_replace($pathToRemove, $toAdd, $filename_mp3);
    }

}

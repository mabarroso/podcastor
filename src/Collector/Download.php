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

    /**
     * [getURL description]
     *
     * @param [type] $filename     [description]
     * @param [type] $pathToRemove [description]
     * @param [type] $toAdd        [description]
     *
     * @return none
     */
    public function getURL($filename, $pathToRemove, $toAdd)
    {
        $filename_mp3 = $this->getMp3Name($filename);

        return str_replace($pathToRemove, $toAdd, $filename_mp3);
    }

    /**
     * [getPends description]
     *
     * @return Array
     */
    public function getPends()
    {
        return glob($this->path.'/*.pend');
    }

    /**
     * [getURLFileContents description]
     *
     * @param [type] $url [description]
     *
     * @return Bolean or Data
     */
    public function getURLFileContents($url)
    {
        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';

        $ch = curl_init();
        $source = $url;
        //curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        //curl_setopt($ch, CURLOPT_REFERER, 'https://www.domain.com/');
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * [downloadPends description]
     *
     * @param boolean $max [description]
     *
     * @return [type]       [description]
     */
    public function downloadPends($max = false)
    {
        $pends = $this->getPends();

        $downloaded = 0;
        foreach ($pends as $filename_pend) {
            $filename_mp3 = str_replace('.pend', '.mp3', $filename_pend);
            $url = file_get_contents($filename_pend);

            $data = $this->getURLFileContents($url, $filename_mp3);
            if ($data) {
                $file = fopen($filename_mp3, "w+");
                fputs($file, $data);
                fclose($file);
                $downloaded++;
                unlink($filename_pend);
            } else {
                if (file_exists($filename_mp3)) unlink($filename_mp3);
            }
            if ($max && ($downloaded >= $max)) return $downloaded;
        }
        return $downloaded;
    }
}

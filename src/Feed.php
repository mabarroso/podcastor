<?php
/**
 * Feed.php
 *
 * PHP version 5.2
 *
 * @category  PodcastCollector
 * @package   Feed
 * @author    mabarroso <mabarroso@mabarroso.com>
 * @copyright 2013 mabarroso.com
 * @license   Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version   GIT: $Id$
 * @link      http://www.mabarroso.com
 * @since     File available since Release 0.1
 */

/**
 * Feed
 *
 * @category  PodcastCollector
 * @package   Feed
 * @author    mabarroso <mabarroso@mabarroso.com>
 * @copyright 2013 mabarroso.com
 * @license   Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version   GIT: $Id$
 * @link      http://www.mabarroso.com
 * @since     File available since Release 0.1
 */
class Feed
{
    private $_filePath;
    private $_fh;

    /**
     * [__construct description]
     *
     * @param [type] $filePath [description]
     */
    public function __construct($filePath)
    {
        $this->setFile($filePath);
    }

    /**
     * [setFile description]
     *
     * @param [type] $filePath [description]
     *
     * @return none
     */
    public function setFile($filePath)
    {
        $this->_filePath = $filePath;

        $this->_fh = fopen($this->_filePath, 'w+');
    }

    /**
     * [unsetFile close]
     *
     * @return none
     */
    public function close()
    {
        fclose($this->_fh);
    }

    /**
     * [addHeader description]
     *
     * @param [type] $title [description]
     * @param [type] $link  [description]
     *
     * @return none
     */
    public function addHeader($title, $link)
    {
        fputs(
            $this->_fh,
<<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
    <channel>
        <title>$title</title>
        <link>$link</link>
EOT
        );
    }
}

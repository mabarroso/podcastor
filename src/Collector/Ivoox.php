<?php
/**
 * Ivoox.php
 *
 * PHP version 5.2
 *
 * @category  PodcastCollector
 * @package   Ivoox
 * @author    mabarroso <mabarroso@mabarroso.com>
 * @copyright 2013 mabarroso.com
 * @license   Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version   GIT: $Id$
 * @link      http://www.mabarroso.com
 * @since     File available since Release 0.1
 */

/**
 * Ivoox
 *
 * @category  PodcastCollector
 * @package   Ivoox
 * @author    mabarroso <mabarroso@mabarroso.com>
 * @copyright 2013 mabarroso.com
 * @license   Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version   GIT: $Id$
 * @link      http://www.mabarroso.com
 * @since     File available since Release 0.1
 */
class Ivoox
{
    const URL_SIGN = '?t=laenoZuleqetpw%3D%3D';
    private $_filePath;

    /**
     * [__construct description]
     *
     */
    public function __construct()
    {
    }

    /**
     * [getItemList description]
     *
     * @param [type] $html [description]
     *
     * @return [type]       [description]
     */
    public function getItemList($html)
    {
        $items = array();

        if (preg_match_all("|class=\"titulo\"[^>]+href=\"([^\"]+)\"[^>]+>([^<]+)<|", $html, $matches)) {
            $n = count($matches[1]);
            for ($i=0; $i < $n; $i++) {
                $items[$matches[1][$i]] = $matches[2][$i];
            }
        }

        return $items;
    }

    /**
     * [getItemData description]
     *
     * @param [type] $html [description]
     *
     * @return [type]       [description]
     */
    public function getItemData($html)
    {
        $data = array(
            'description' => '',
            'url' => '',
            'title' => '',
            'image' => '',
            'date'  => '',
            'media' => ''
        );

        if (preg_match("|meta name=\"description\" content=\"([^\"]+)\"|", $html, $matches)) {
            $data['description'] = $matches[1];
        }
        if (preg_match("|meta content=\"([^\"]+)\" property=\"og:url\"|", $html, $matches)) {
            $data['url'] = $matches[1];
        }
        if (preg_match("|meta content=\"([^\"]+)\" property=\"og:title\"|", $html, $matches)) {
            $data['title'] = $matches[1];
        }
        if (preg_match("|el ([0-9]{2}/[0-9]{2}/[0-9]{4}), en|", $html, $matches)) {
            $data['date'] = $matches[1];
        }
        if (preg_match("|meta content=\"([^\"]+)\" property=\"og:image\"|", $html, $matches)) {
            $data['image'] = $matches[1];
        }

        $data['media'] = $this->transformURL2MP3($data['url']).self::URL_SIGN;

        return $data;
    }

    /**
     * [transformURL2MP3 description]
     *
     * @param [type] $url [description]
     *
     * @return [type]      [description]
     */
    public function transformURL2MP3($url)
    {
        if (preg_match("|(.*)-audios-mp3_rf_([0-9]+)_1.html|", $url, $matches)) {
            return $matches[1].'_md_'.$matches[2].'_1.mp3';
        } else {
            return false;
        }

    }

}

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
    private $_filePath;

    /**
     * [__construct description]
     *
     */
    public function __construct()
    {
    }

    public function getItemList($html)
    {
        $items = array();

        if (preg_match_all("|class=\"titulo\"[^>]+href=\"([^\"]+)\"[^>]+>([^<]+)<|", $html, $matches)) {

            $n = count($matches)-1;
            for ($i=0; $i < $n; $i++) {
                $items[$matches[1][$i]] = $matches[2][$i];
            }
        }

        return $items;
    }

    public function getItemData($html)
    {
        $data = array(
            'description' => '',
            'url' => '',
            'title' => '',
            'image' => ''            
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
        if (preg_match("|meta content=\"([^\"]+)\" property=\"og:image\"|", $html, $matches)) {
            $data['image'] = $matches[1];
        }

        return $data;
    }


}

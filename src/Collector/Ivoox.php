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

date_default_timezone_set('Europe/Paris');

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
    const URL_RSS = 'http://api.ivoox.com/?function=getAudiosByWordsAndFilters&idPodcast=';

    /**
     * [__construct description]
     *
     */
    public function __construct()
    {
    }

    /**
     * [getIdfromPublicURL description]
     *
     * @param [type] $url [description]
     *
     * @return [type]      [description]
     */
    public function getIdfromPublicURL($url)
    {
        if (preg_match("|sq_f1([0-9]+)_1|", $url, $matches)) {
            return $matches[1];
        }

        return false;
    }

    /**
     * [getURLForId description]
     *
     * @param [type] $id [description]
     *
     * @return [type]     [description]
     */
    public function getURLForId($id)
    {
        return self::URL_RSS.$id;
    }


    /**
     * [getItemData description]
     *
     * @param [type] $html [description]
     *
     * @return [type]       [description]
     */
    public function getItems($html)
    {
        $items = array();

        $query = new SimpleXMLElement($html);

        if ($query->stat != 'ok') return $items;

        foreach ($query->results->audio as $audio) {
            $data = array(
                'description' => '',
                'url' => '',
                'title' => '',
                'image' => '',
                'date'  => '',
                'duration' => '',
                'media' => '',
                'podcasttitle' => '',
                'channeltitle' => ''
            );

            $data['description'] = (string) $audio->description;
            $data['url'] = (string)  $audio->shareurl;
            $data['title'] = (string) $audio->title;
            $data['image'] = (string) $audio->image;

            list($d, $m, $y) = explode('/', (string) $audio->date);
            $data['date'] = date(DATE_RFC2822, strtotime("$y-$m-$d"));

            $data['duration'] = (string) $audio->duration;
            $data['media'] = (string) $audio->file;
            $data['podcasttitle'] = (string) $audio->podcasttitle;
            $data['channeltitle'] = (string) $audio->channeltitle;

            $items[] = $data;
        }

        return $items;
    }

}

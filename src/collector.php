<?php
require_once 'Collector/Feed.php';
require_once 'Collector/Ivoox.php';


$tasks = array(
//    array('Betabeers', 'http://www.ivoox.com/podcast-podcast-betabeers_sq_f189550_1.html', '../tmp/betabeers.xml'),
    array('Terror y nada más', 'http://www.ivoox.com/podcast-terror-nada-mas_sq_f1407_1.html', '../tmp/tynm.xml'),
);

$collector = new Collector();

foreach ($tasks as $task) {
    $collector->task($task[0], $task[1], $task[2]);
}





class Collector
{
    public $currentHostURL;
    public $feed;
    public $ivoox;

    /**
     * [__construct description]
     */
    function __construct()
    {
        $this->feed = new Feed();
        $this->ivoox = new Ivoox();
    }

    /**
     * [task description]
     *
     * @param [type] $title [description]
     * @param [type] $url   [description]
     * @param [type] $xml   [description]
     *
     * @return none
     */
    function task($title, $url, $xml)
    {
        $this->setHost($url);
        $this->feed->setFile($xml);

        $this->feed->open();
        $this->feed->addHeader($title, $url);

        $podcast_home_html = file_get_contents($url);
        $item_list = $this->ivoox->getItemList($podcast_home_html);
        foreach ($item_list as $item_url => $item_title) {
            $item_html = file_get_contents($this->validateHost($item_url));
            $item_data = $this->ivoox->getItemData($item_html);

            if ($item_data) {
                $this->feed->addItem(
                    $item_data['title'],
                    $title,
                    $item_data['description'],
                    $item_data['description'],
                    $item_data['image'],
                    $item_data['media'],
                    0,
                    $item_data['url'],
                    0,
                    0
                );
            }
        }
        $this->feed->addFooter();
        $this->feed->close();
    }

    /**
     * [setHost description]
     *
     * @param [type] $url [description]
     *
     * @return none
     */
    function setHost($url)
    {
        $parts = parse_url($url);
        $this->currentHostURL = "{$parts['scheme']}://{$parts['host']}";
    }

    /**
     * [validateHost description]
     *
     * @param [type] $url [description]
     *
     * @return [type]      [description]
     */
    function validateHost($url)
    {
        if (!$url) return false;
        if (!preg_match('|^http://|', $url))
            return $this->currentHostURL . '/'. $url;
        else
            return $url;
    }

}

<?php
require_once 'Collector/Feed.php';
require_once 'Collector/Ivoox.php';


$tasks = array(
    array('Betabeers', 'http://www.ivoox.com/podcast-podcast-betabeers_sq_f189550_1.html', './feeds/betabeers.xml'),
    array('Terror y nada más', 'http://www.ivoox.com/podcast-terror-nada-mas_sq_f1407_1.html', './feeds/tynm.xml'),
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
     * [log description]
     *
     * @param [type] $msg [description]
     *
     * @return [type]      [description]
     */
    function log($msg)
    {
        echo sprintf("%s %s\n", date('d/m/Y h:m:s'), $msg);
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
        $this->log("$title - $url");

        $this->feed->setFile($xml);

        $podcast_html = file_get_contents($this->ivoox->getURLForId($this->ivoox->getIdfromPublicURL($url)));

        $items = $this->ivoox->getItems($podcast_html);

        $this->log("\tFound ".count($items)." items");

        if (count($items) == 0) return false;

        $this->feed->open();
        $this->feed->addHeader($items[0]['podcasttitle'], $url);

        foreach ($items as $item_data) {
            if ($item_data) {
                $this->feed->addItem(
                    $item_data['title'],
                    $title,
                    $item_data['description'],
                    $item_data['description'],
                    $item_data['image'],
                    $item_data['media'],
                    $item_data['filesize'],
                    $item_data['url'],
                    $item_data['date'],
                    $item_data['duration']
                );
            }
        }
        $this->feed->addFooter();
        $this->feed->close();
    }

}

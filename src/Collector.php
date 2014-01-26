<?php
require_once 'Collector/Feed.php';
require_once 'Collector/Ivoox.php';
require_once 'Collector/Download.php';

define('DATA_PATH', './data/');

$tasks = array(
    array('Betabeers',                   'http://www.ivoox.com/podcast-podcast-betabeers_sq_f189550_1.html',                         'betabeers.xml',            'http://www.almianos.net/podcastor/'),
    //array('Terror y nada más',           'http://www.ivoox.com/podcast-terror-nada-mas_sq_f1407_1.html',                           'tynm.xml',                 'http://www.almianos.net/podcastor/'),
    array('Laiseca',                     'http://www.ivoox.com/podcast-cuentos-terror-narrados-alberto-laiseca_sq_f142367_1.html',    'laiseca.xml',             'http://www.almianos.net/podcastor/'),
    array('Cuentos de terror',           'http://www.ivoox.com/podcast-cuentos-de-terror_sq_f123688_1.html',                          'cuentos-de-terror.xml',   'http://www.almianos.net/podcastor/'),
    array('Iniciador',                   'http://www.ivoox.com/podcast-ponencias-iniciador_sq_f11368_1.html',                         'iniciador.xml',           'http://www.almianos.net/podcastor/'),
    array('TED ES',                      'http://www.ivoox.com/podcast-conferencias-ted-espanol_sq_f197583_1.html',                   'ted-es.xml',              'http://www.almianos.net/podcastor/'),
    array('Emprendimiento e innovación', 'http://www.ivoox.com/podcast-emprendimientos-e-innovacion_sq_f1112_1.html',                 'emp-inno.xml',            'http://www.almianos.net/podcastor/'),
    array('Emprendeduría EOI',           'http://www.ivoox.com/podcast-emprendeduria-eoi_sq_f133742_1.html',                          'eoi.xml',                 'http://www.almianos.net/podcastor/'),
);

$collector = new Collector();

foreach ($tasks as $task) {
    $collector->task($task[0], $task[1], $task[2], $task[3]);
}

class Collector
{
    public $currentHostURL;
    public $feed;
    public $ivoox;
    public $download;

    /**
     * [__construct description]
     */
    function __construct()
    {
        $this->feed = new Feed();
        $this->ivoox = new Ivoox();
        $this->download = new Download();
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
     * @param [type] $title    [description]
     * @param [type] $url      [description]
     * @param [type] $xml      [description]
     * @param [type] $url_base [description]
     *
     * @return none
     */
    function task($title, $url, $xml, $url_base)
    {
        $this->log("$title - $url");

        $podcast_id = $this->ivoox->getIdfromPublicURL($url);
        $podcast_html = file_get_contents($this->ivoox->getURLForId($podcast_id));

        $items = $this->ivoox->getItems($podcast_html);

        $this->log("\tFound ".count($items)." items");

        if (count($items) == 0) return false;

        $image = $this->ivoox->getImage(file_get_contents($url));

        if (!$image) $image = $items[0]['image'];

        $this->download->setPath(DATA_PATH.$podcast_id);
        $this->feed->setFile(DATA_PATH.$xml);
        $this->feed->open();
        $this->feed->addHeader($items[0]['podcasttitle'], $url, $image);
        foreach ($items as $item_data) {
            if ($item_data) {
                $this->download->add($item_data['id'], $item_data['media']);
                $this->feed->addItem(
                    $item_data['title'],
                    $title,
                    $item_data['description'],
                    $item_data['description'],
                    $item_data['image'],
                    $this->download->getURL($item_data['id'], DATA_PATH, $url_base),
                    $item_data['filesize'],
                    $item_data['url'],
                    $item_data['date'],
                    $item_data['duration'],
                    $item_data['media']
                );
            }
        }
        $this->feed->addFooter();
        $this->feed->close();
    }

}

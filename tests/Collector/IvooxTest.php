<?php
/**
 * IvooxTest
 *
 * PHP version 5.2
 *
 * @category   PodcastCollector
 * @package    Ivoox
 * @subpackage Tests
 * @author     mabarroso <mabarroso@mabarroso.com>
 * @copyright  2013 mabarroso.com
 * @license    Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version    GIT: $Id$
 * @link       http://www.mabarroso.com
 * @since      File available since Release 0.1
 */

require_once 'src/Collector/Ivoox.php';

/**
 * IvooxTest
 *
 * @category   PodcastCollector
 * @package    Ivoox
 * @subpackage Tests
 * @author     mabarroso <mabarroso@mabarroso.com>
 * @copyright  2013 mabarroso.com
 * @license    Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version    GIT: $Id$
 * @link       http://www.mabarroso.com
 * @since      File available since Release 0.1
 */
class IvooxTest extends PHPUnit_Framework_TestCase
{
    protected $subject;

    /**
     * Constructor
     *
     * @return none
     */
    protected function setUp()
    {
        $this->subject = new Ivoox();
    }

    /**
     * [testInstanceType description]
     *
     * @return none
     */
    public function testInstanceType()
    {
        $this->assertTrue($this->subject instanceof Ivoox);
    }

    /**
     * [testGetIdfromPublicURL description]
     *
     * @return none
     */
    public function testGetIdfromPublicURL()
    {
        $this->assertEquals(
            '29017',
            $this->subject->getIdfromPublicURL('http://www.ivoox.com/podcast-audiorelatos-audiolibros-de-terror-tynm-t-3_sq_f129017_1.html')
        );
        $this->assertEquals(
            '407',
            $this->subject->getIdfromPublicURL('http://www.ivoox.com/podcast-terror-nada-mas_sq_f1407_1.html')
        );
    }


    /**
     * [testGetURLForId description]
     *
     * @return none
     */
    public function testGetURLForId()
    {
        $this->assertEquals(
            'http://api.ivoox.com/?function=getAudiosByWordsAndFilters&idPodcast=29017',
            $this->subject->getURLForId('29017')
        );
    }

    /**
     * [testGetItemList description]
     *
     * @return none
     */
    public function testGetItems()
    {
        $items = $this->subject->getItems(file_get_contents('tests/_files/getAudiosByWordsAndFilters.xml'));

        $this->assertEquals(19, count($items));

        $this->assertEquals(
            array(
                'description' => "Relato Perteneciente A La Biblioteca Universal Del Terror Y El Misterio Adaptado Por TyNM\nwww.terrorynadamas.com\nwww.facebook.com/terrorynadamas",
                'url' => 'http://www.ivoox.com/tynm-19-pedro-montero-emision-de-madrugada-audios-mp3_rf_950626_1.html',
                'title' => 'TyNM 19 Pedro Montero - Emision De Madrugada',
                'image' => 'http://images2.ivoox.com/canales/6991324338810g.jpg',
                'media' => 'http://www.ivoox.com/tynm-19-pedro-montero-emision-de-madrugada_mn_950626_1.mp3',
                'date'  => 'Tue, 20 Dec 2011 00:00:00 +0100',
                'duration' => '36:44',
                'filesize' => '50',
                'podcasttitle' => 'Audiorelatos / Audiolibros De Terror - TyNM T.3',
                'channeltitle' => 'Radioteatro De Horror - Terror y Nada Más'
            ),
            $items[0]
        );
    }
}

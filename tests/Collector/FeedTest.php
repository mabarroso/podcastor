<?php
/**
 * FeedTest
 *
 * PHP version 5.2
 *
 * @category   PodcastCollector
 * @package    Feed
 * @subpackage Tests
 * @author     mabarroso <mabarroso@mabarroso.com>
 * @copyright  2013 mabarroso.com
 * @license    Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version    GIT: $Id$
 * @link       http://www.mabarroso.com
 * @since      File available since Release 0.1
 */

require_once 'src/Collector/Feed.php';

/**
 * FeedTest
 *
 * @category   PodcastCollector
 * @package    Feed
 * @subpackage Tests
 * @author     mabarroso <mabarroso@mabarroso.com>
 * @copyright  2013 mabarroso.com
 * @license    Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version    GIT: $Id$
 * @link       http://www.mabarroso.com
 * @since      File available since Release 0.1
 */
class FeedTest extends PHPUnit_Framework_TestCase
{
    protected $subject;

    const FILEPATH = 'tmp';
    const FILENAME = 'tmp/feed.xml';

    /**
     * Constructor
     *
     * @return none
     */
    protected function setUp()
    {
        if (!file_exists(self::FILEPATH)) mkdir(self::FILEPATH, 0777, true);
        $this->subject = new Feed(self::FILENAME);
    }

    /**
     * [testInstanceType description]
     *
     * @return none
     */
    public function testInstanceType()
    {
        $this->assertTrue($this->subject instanceof Feed);
    }

    /**
     * [testSetFile description]
     *
     * @return none
     */
    public function testSetFile()
    {
        $this->assertTrue(file_exists(self::FILENAME), "File ".self::FILENAME." must be created by constructor");
        $this->subject->close();
        $this->subject->setFile(self::FILENAME);
        $this->assertTrue(file_exists(self::FILENAME), "File ".self::FILENAME." must be created");
    }

    /**
     * [testOpen description]
     *
     * @return none
     */
    public function testOpen()
    {
        $this->subject->close();
        $this->subject->open();
        $this->assertTrue(file_exists(self::FILENAME), "File ".self::FILENAME." must be created");
    }

    /**
     * [testSanitize description]
     *
     * @return none
     */
    public function testSanitize()
    {
        $this->assertEquals('&amp;', $this->subject->sanitize('&'));
    }

    /**
     * [testAddHeader description]
     *
     * @return none
     */
    public function testAddHeader()
    {
        $this->subject->open();
        $this->subject->addHeader('title &', 'link', 'image');
        $this->subject->close();
        $this->assertFileEquals('tests/_files/expected_feed_testAddHeader.xml', self::FILENAME);
    }

    /**
     * [testAddHeader description]
     *
     * @return none
     */
    public function testAddFooter()
    {
        $this->subject->open();
        $this->subject->addFooter();
        $this->subject->close();
        $this->assertFileEquals('tests/_files/expected_feed_testAddFooter.xml', self::FILENAME);
    }

    /**
     * [testAddItem description]
     *
     * @return none
     */
    public function testAddItem()
    {
        $this->subject->open();
        $this->subject->addItem(
            'Shake Shake & Shake Your Spices',
            'John Doe &',
            'A short & primer on table spices',
            'This week & we talk about salt and pepper shakers, comparing and contrasting pour rates, construction materials, and overall aesthetics. Come and join the party!',
            'http://example.com/podcasts/everything/AllAboutEverything/Episode1.jpg',
            'http://example.com/podcasts/everything/AllAboutEverythingEpisode3.m4a',
            '8727310',
            'http://example.com/podcasts/archive/aae20050615.m4a',
            'Wed, 15 Jun 2005 19:00:00 GMT',
            '7:04',
            'xta'
        );
        $this->subject->close();
        $this->assertFileEquals('tests/_files/expected_feed_testAddItem.xml', self::FILENAME);
    }
}

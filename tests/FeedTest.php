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

require_once 'src/Feed.php';

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
        //$this->unlink(self::FILENAME);
        //$this->assertFalse(file_exists(self::FILENAME), "File ".self::FILENAME." must be deleted");
        $this->subject->setFile(self::FILENAME);
        $this->assertTrue(file_exists(self::FILENAME), "File ".self::FILENAME." must be created");
    }

    /**
     * [testAddHeader description]
     *
     * @return none
     */
    public function testAddHeader()
    {
        $this->subject->addHeader('title', 'link');
        $this->subject->close();
        $this->assertFileEquals('tests/_files/expected_feed_testAddHeader.xml', self::FILENAME);
    }

}

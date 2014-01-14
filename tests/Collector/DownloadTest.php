<?php
/**
 * DownloadTest
 *
 * PHP version 5.2
 *
 * @category   PodcastCollector
 * @package    Download
 * @subpackage Tests
 * @author     mabarroso <mabarroso@mabarroso.com>
 * @copyright  2013 mabarroso.com
 * @license    Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version    GIT: $Id$
 * @link       http://www.mabarroso.com
 * @since      File available since Release 0.1
 */

require_once 'src/Collector/Download.php';

/**
 * DownloadTest
 *
 * @category   PodcastCollector
 * @package    Download
 * @subpackage Tests
 * @author     mabarroso <mabarroso@mabarroso.com>
 * @copyright  2014 mabarroso.com
 * @license    Apache 2 License http://www.apache.org/licenses/LICENSE-2.0.html
 * @version    GIT: $Id$
 * @link       http://www.mabarroso.com
 * @since      File available since Release 0.1
 */
class DownloadTest extends PHPUnit_Framework_TestCase
{
    protected $subject;

    const FILEPATH = 'tmp/download';

    /**
     * Constructor
     *
     * @return none
     */
    protected function setUp()
    {
        if (!file_exists(self::FILEPATH)) mkdir(self::FILEPATH, 0777, true);
        array_map('unlink', glob(self::FILEPATH.'/*.*'));
        $this->subject = new Download();
    }

    /**
     * [testInstanceType description]
     *
     * @return none
     */
    public function testInstanceType()
    {
        $this->assertTrue($this->subject instanceof Download);
    }

    /**
     * [testSetPath description]
     *
     * @return none
     */
    public function testSetPath()
    {
        $this->subject->setPath(self::FILEPATH);
        $this->assertEquals(self::FILEPATH, $this->subject->path);
    }

    /**
     * [testGetPendName description]
     *
     * @return none
     */
    public function testGetPendName()
    {
        $this->subject->setPath(self::FILEPATH);
        $this->assertEquals(self::FILEPATH.'/name.pend', $this->subject->getPendName('name'));
    }

    /**
     * [testGetMp3Name description]
     *
     * @return none
     */
    public function testGetMp3Name()
    {
        $this->subject->setPath(self::FILEPATH);
        $this->assertEquals(self::FILEPATH.'/name.mp3', $this->subject->getMp3Name('name'));
    }

    /**
     * [testAdd description]
     *
     * @return none
     */
    public function testAdd()
    {
        $this->subject->setPath(self::FILEPATH);

        $this->subject->add('new', 'http://foo/bar/new');
        $this->assertTrue(file_exists(self::FILEPATH.'/new.pend'));
        $this->assertFalse(file_exists(self::FILEPATH.'/unnecessary.mp3'));
        $this->assertEquals('http://foo/bar/new', file_get_contents(self::FILEPATH.'/new.pend'));

        file_put_contents(self::FILEPATH.'/exists.pend', 'http://foo/bar/exists');
        $this->subject->add('exists', 'http://foo/bar/exists');
        $this->assertTrue(file_exists(self::FILEPATH.'/exists.pend'));
        $this->assertFalse(file_exists(self::FILEPATH.'/unnecessary.mp3'));

        file_put_contents(self::FILEPATH.'/downloaded.mp3', 'http://foo/bar/downloaded');
        $this->subject->add('downloaded', 'http://foo/bar/downloaded');
        $this->assertFalse(file_exists(self::FILEPATH.'/downloaded.pend'));
        $this->assertTrue(file_exists(self::FILEPATH.'/downloaded.mp3'));
    }

    /**
     * [testGetURL description]
     *
     * @return none
     */
    public function testGetURL()
    {
        $this->subject->setPath(self::FILEPATH.'other');
        $this->assertEquals('http://aaaa/other/filename.mp3', $this->subject->getURL('filename', self::FILEPATH, 'http://aaaa/'));
    }

    /**
     * [testGetPends description]
     *
     * @return none
     */
    public function testGetPends()
    {
        $this->subject->setPath(self::FILEPATH);

        file_put_contents(self::FILEPATH.'/1.pend', 'http://foo/bar/1');
        file_put_contents(self::FILEPATH.'/2.pend', 'http://foo/bar/2');
        file_put_contents(self::FILEPATH.'/3.pend', 'http://foo/bar/3');

        $pends = $this->subject->getPends();

        $this->assertEquals(
            array(
                self::FILEPATH.'/1.pend',
                self::FILEPATH.'/2.pend',
                self::FILEPATH.'/3.pend'
            ),
            $pends
        );
    }

    /**
     * [testDownloadPends description]
     *
     * @return none
     */
    public function testDownloadPends()
    {

        $subject = $this->getMock('Download', array('getURLFileContents'));
        $subject->expects($this->any())
            ->method('getURLFileContents')
            ->will($this->returnValue(true));

        $subject->setPath(self::FILEPATH);

        file_put_contents(self::FILEPATH.'/1.pend', 'http://foo/bar/1');
        file_put_contents(self::FILEPATH.'/2.pend', 'http://foo/bar/2');
        file_put_contents(self::FILEPATH.'/3.pend', 'http://foo/bar/3');

        $subject->downloadPends(1);

        $this->assertTrue(file_exists(self::FILEPATH.'/1.mp3'));
        $this->assertFalse(file_exists(self::FILEPATH.'/1.pend'));
        $this->assertTrue(file_exists(self::FILEPATH.'/2.pend'));
        $this->assertTrue(file_exists(self::FILEPATH.'/3.pend'));

        $subject->downloadPends();

        $this->assertTrue(file_exists(self::FILEPATH.'/2.mp3'));
        $this->assertFalse(file_exists(self::FILEPATH.'/2.pend'));
        $this->assertTrue(file_exists(self::FILEPATH.'/3.mp3'));
        $this->assertFalse(file_exists(self::FILEPATH.'/3.pend'));
    }

}

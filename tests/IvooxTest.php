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

require_once 'src/Ivoox.php';

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

}
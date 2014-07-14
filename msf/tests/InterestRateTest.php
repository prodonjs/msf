<?php
namespace msf\tests;

class InterestRateTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        parent::setUp();

        $this->testFile = TEST_DATA_PATH . DS . 'interest_rates.html';
    } // end setUp()

    public function testParse() {
        $interestRate = new \msf\models\InterestRate($this->testFile);
        $rates = $interestRate->parse();
        $this->assertEquals(array(
            'treasury' => array('2014-07-11 11:07 PM', 2.129, 2.519),
            'swap' => array('2014-07-11 11:07 PM', 2.199, 2.629)
        ), $rates);
    } // end testParse()
} // end class InterestRateTest

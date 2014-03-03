<?php
namespace msf\tests;

class FileDataSourceTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        parent::setUp();
        $this->fileDataSource = new \msf\models\FileDataSource(TEST_DATA_PATH, 'json');
    } // end setUp()

    public function testGetFileName() {
        $expected = TEST_DATA_PATH . DS . 'my_model_1.json';
        $this->assertEquals($expected,
            $this->fileDataSource->getFileName('my_model', 1)
        );
    } // end testGetFileName()

    public function testWrite() {
        $expected = TEST_DATA_PATH . DS . 'my_model_1.json';
        $data = array(
            'id' => 1,
            'my_field' => 'value 1',
            'field_2' => 100,
            'created' => '2014-02-23 00:00:00'
        );
        $modelName = 'my_model';

        $result = $this->fileDataSource->write($data, $modelName);
        $this->assertEquals($expected, $result);
        $this->assertTrue(file_exists($result));
    } // end testWrite_successful()

    public function testWrite_missing_id() {
       $data = array(
            'my_field' => 'value 1',
            'field_2' => 100,
            'created' => '2014-02-23 00:00:00'
        );
        $modelName = 'my_model';

        $this->setExpectedException('RuntimeException', 'Data must have id field set');
        $this->fileDataSource->write($data, $modelName);
    } // end testWrite_missing_id()

    public function testWrite_bad_path() {
       $data = array(
            'id' => 1,
            'my_field' => 'value 1',
            'field_2' => 100,
            'created' => '2014-02-23 00:00:00'
        );
        $modelName = 'badpath' . DS . 'my_model';
        $expected = TEST_DATA_PATH . DS . 'badpath' . DS . 'my_model_1.json';

        $this->setExpectedException('RuntimeException', "{$expected} cannot be opened for writing");
        $this->fileDataSource->write($data, $modelName);
    } // end testWrite_missing_id()

    public function testRead() {
        $expected = array(
            'id' => 2,
            'my_field' => 'value 2',
            'field_2' => 500,
            'created' => '2014-02-23 00:00:00'
        );

        $data = $this->fileDataSource->read('read_me', 2);

        $this->assertEquals($expected, $data);
    } // end testRead()

    public function testRead_exception() {
        $expected = TEST_DATA_PATH . DS . 'no_model_999.json';
        $this->setExpectedException('RuntimeException', "{$expected} cannot be opened for readin");
        $data = $this->fileDataSource->read('no_model', 999);
    } // end testRead()

    public function testDelete() {
        $fileToDelete = $this->fileDataSource->getFileName('my_model', 3);
        copy($fileToDelete, "{$fileToDelete}.copy");

        $this->assertTrue($this->fileDataSource->delete('my_model', 3));
        $this->assertFalse(file_exists($fileToDelete));

        rename("{$fileToDelete}.copy", $fileToDelete);
    } // end testDelete()

    public function testReadAll() {
        $data = $this->fileDataSource->readAll('my_model');

        $this->assertEquals(3, count($data));
    } // end testReadAll()

    public function testReadAll_limit_sort() {
        $data = $this->fileDataSource->readAll('my_model', 2, 'my_field');

        $this->assertEquals(2, count($data));
        $this->assertEquals(2, $data[1]['id']);
    } // end testReadAll()

    public function testSortData_asc() {
        $firstEntry = array(
            'id' => 3,
            'my_field' => 'value 3',
            'field_2' => 30,
            'created' => '2014-02-23 00:00:00'
        );
        $data = $this->fileDataSource->readAll('my_model');

        $sortedData = $this->fileDataSource->sortData($data, 'field_2', 'ASC');

        $this->assertEquals($firstEntry, $sortedData[0]);
    } // end testSortData_asc()

    public function testSortData_desc() {
        $firstEntry = array(
            'id' => 3,
            'my_field' => 'value 3',
            'field_2' => 30,
            'created' => '2014-02-23 00:00:00'
        );
        $data = $this->fileDataSource->readAll('my_model');

        $sortedData = $this->fileDataSource->sortData($data, 'my_field', 'DESC');

        $this->assertEquals($firstEntry, $sortedData[0]);
    } // end testSortData_desc()

} // end class FileDataSourceTest

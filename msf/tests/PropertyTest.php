<?php
namespace msf\tests;

class PropertyTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        parent::setUp();

        $this->testDataSource = new \msf\models\FileDataSource(TEST_DATA_PATH);
        $this->property = new \msf\models\Property($this->testDataSource);
        $this->property->name = 'Property Name';
        $this->property->city = 'City';
        $this->property->state = 'AA';
        $this->property->type = 'Any Industry';
        $this->property->amountFinanced = 350000;
        $this->property->description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eget fringilla nisl. ";
        $this->property->description .= "Integer vitae viverra risus. Nam tincidunt ullamcorper nisl, non fermentum dui venenatis et. ";
        $this->property->description .= "Praesent mollis enim metus, a vestibulum neque pharetra sed. Mauris nec nulla sit amet orci consequat ";
        $this->property->description .= "scelerisque. Nulla facilisis semper ipsum, vel laoreet est congue ut.";
    } // end setUp()

    public function testValidation() {
        $this->assertTrue($this->property->validate());
        $this->assertEmpty($this->property->validationErrors);
    } // end testValidation_successful()

    public function testValidation_unsuccessful() {
        $this->property->name = '';
        $this->property->city = '';
        $this->property->state = 'ABC';
        $this->property->type = '';
        $this->property->amountFinanced = -500;
        $this->assertFalse($this->property->validate());
        $this->assertNotEmpty($this->property->validationErrors);
    } // end testValidation_unsuccessful()

    public function testSave() {
        $fileName = $this->property->save();
        $id = $this->property->id;

        $this->assertNotEmpty($fileName);
        $this->assertNotEmpty($id);
    } // end testSave()

    public function testGet() {
        $property = \msf\models\Property::Get('test1', $this->testDataSource);

        $this->assertInstanceOf('msf\models\Property', $property);
        $this->assertEquals($property->id, 'test1');
    } // end testGet()

    public function testFindAll() {
        $properties = \msf\models\Property::FindAll($this->testDataSource);

        $this->assertEquals(4, count($properties));
        $this->assertInstanceOf('msf\models\Property', $properties[0]);
    } // end testFindAll()

} // end class PropertyTest

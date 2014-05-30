<?php
namespace msf\tests;

class ImageTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        parent::setUp();
        $this->imagePng = new \msf\models\Image(
            TEST_DATA_PATH . DS . 'images' . DS . 'png_test_image.png'
        ); // 300 x 168
        $this->imageJpg = new \msf\models\Image(
            TEST_DATA_PATH . DS . 'images' . DS . 'jpg_test_image.jpg'
        ); // 1800 x 1800
        copy($this->imageJpg->fullPath, "{$this->imageJpg->fullPath}.bak");
        copy($this->imagePng->fullPath, "{$this->imagePng->fullPath}.bak");
    } // end setUp()

    public function tearDown() {
        parent::tearDown();
        rename("{$this->imageJpg->fullPath}.bak", $this->imageJpg->fullPath);
        rename("{$this->imagePng->fullPath}.bak", $this->imagePng->fullPath);
    }

    private function _getImageSize($filePath) {
        $info = getimagesize($filePath);
        return array(
            'width' => $info[0],
            'height' => $info[1]
        );
    }

    public function testScaleImage() {
        $this->assertTrue($this->imageJpg->scaleImage(400, 300, false));
        $this->assertTrue($this->imagePng->scaleImage(300, 400, false));
        $this->assertTrue($this->imageJpg->scaleImage(50, 100, true));
        $this->assertTrue($this->imagePng->scaleImage(75, 75, true));

        $this->assertFileExists($this->imageJpg->thumbnailPath);
        $this->assertFileExists($this->imagePng->thumbnailPath);

        $jpgFullSize = $this->_getImageSize($this->imageJpg->fullPath);
        $jpgThumbSize = $this->_getImageSize($this->imageJpg->thumbnailPath);
        $pngFullSize = $this->_getImageSize($this->imagePng->fullPath);
        $pngThumbSize = $this->_getImageSize($this->imagePng->thumbnailPath);

        $this->assertEquals(
            array('width' => 400, 'height' => 224), $jpgFullSize
        );
        $this->assertEquals(
            array('width' => 50, 'height' => 28), $jpgThumbSize
        );
        $this->assertEquals(
            array('width' => 400, 'height' => 400), $pngFullSize
        );
        $this->assertEquals(
            array('width' => 75, 'height' => 75), $pngThumbSize
        );

        unlink($this->imageJpg->thumbnailPath);
        unlink($this->imagePng->thumbnailPath);
    }

    public function testCropToDimensions() {
        $this->assertTrue($this->imageJpg->cropToDimensions(66, 0, 166, 168));
        $jpegCrop = $this->_getImageSize($this->imageJpg->fullPath);
        $this->assertEquals(166, $jpegCrop['width']);
        $this->assertEquals(168, $jpegCrop['height']);

        $this->assertTrue($this->imagePng->cropToDimensions(0, 361, 600, 100));
        $pngCrop = $this->_getImageSize($this->imagePng->fullPath);
        $this->assertEquals(600, $pngCrop['width']);
        $this->assertEquals(100, $pngCrop['height']);
    }
} // end class ImageTest

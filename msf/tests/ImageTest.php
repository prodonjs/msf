<?php
namespace msf\tests;

class ImageTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        parent::setUp();
        $this->imagePng = new \msf\models\Image(TEST_DATA_PATH . DS . 'images' . DS . 'png_test_image.png');
        $this->imageJpg = new \msf\models\Image(TEST_DATA_PATH . DS . 'images' . DS . 'jpg_test_image.jpg');
    } // end setUp()

    private function _getImageSize($filePath) {
        $info = getimagesize($filePath);
        return array(
            'width' => $info[0],
            'height' => $info[1]
        );
    }

    public function testGenerateThumbnail() {
        $this->assertTrue($this->imageJpg->generateThumbnail(100, 100));
        $this->assertTrue($this->imagePng->generateThumbnail(300, 300));

        $this->assertFileExists($this->imageJpg->thumbnailPath);
        $wideThumbnailSize = $this->_getImageSize($this->imageJpg->thumbnailPath);
        $this->assertEquals(100, $wideThumbnailSize['width']);

        $this->assertFileExists($this->imagePng->thumbnailPath);
        $tallThumbnailsize = $this->_getImageSize($this->imagePng->thumbnailPath);
        $this->assertEquals(300, $tallThumbnailsize['height']);

        unlink($this->imageJpg->thumbnailPath);
        unlink($this->imagePng->thumbnailPath);
    }

    public function testCropToDimensions() {
        copy($this->imageJpg->fullPath, "{$this->imageJpg->fullPath}.bak");
        copy($this->imagePng->fullPath, "{$this->imagePng->fullPath}.bak");

        $this->assertTrue($this->imageJpg->cropToDimensions(66, 0, 166, 168));
        $jpegCrop = $this->_getImageSize($this->imageJpg->fullPath);
        $this->assertEquals(166, $jpegCrop['width']);
        $this->assertEquals(168, $jpegCrop['height']);

        $this->assertTrue($this->imagePng->cropToDimensions(0, 361, 600, 100));
        $pngCrop = $this->_getImageSize($this->imagePng->fullPath);
        $this->assertEquals(600, $pngCrop['width']);
        $this->assertEquals(100, $pngCrop['height']);

        rename("{$this->imageJpg->fullPath}.bak", $this->imageJpg->fullPath);
        rename("{$this->imagePng->fullPath}.bak", $this->imagePng->fullPath);
    }
} // end class ImageTest

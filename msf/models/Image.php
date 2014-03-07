<?php
namespace msf\models;
/**
 * Class for representing an Image
 *
 * @author Jason
 */
class Image {
    const THUMBNAIL_PREFIX = 'thumb-';

    /**
     * Basename of image file
     * @var string
     */
    public $name;

    /**
     * Fully qualified image path
     * @var string
     */
    public $fullPath;

    /**
     * Fully qualified thumbnail image path
     * @var string
     */
    public $thumbnailPath;

    /**
     * Image file extension (jpg|png)
     * @var string
     */
    public $type;

    /**
     * Image size
     * @var int
     */
    public $sizeInBytes;

    /**
     * Creates an Image object from the specified path
     * @param string $filePath
     */
    public function __construct($filePath) {
        if(!file_exists($filePath)) {
            throw new \RuntimeException("{$filePath} does not exist");
        }
        if(!self::IsValidImage($filePath)) {
            throw new \RuntimeException("{$filePath} must be a valid JPG or PNG image");
        }
        $this->fullPath = $filePath;
        $this->name = basename($this->fullPath);
        $this->sizeInBytes = filesize($this->fullPath);
        $this->type = substr($this->fullPath, -3);
        $this->thumbnailPath = dirname($this->fullPath) . DS . self::THUMBNAIL_PREFIX . $this->name;
    } // end __construct

    /**
     * Generates a thumbnail from the current image using the specified prefix
     * and scaling to fit within the dimensions provided
     * @param string $prefix
     * @param int $width
     * @param int $height
     * @return boolean
     */
    public function generateThumbnail($width, $height) {
        $image = $this->_getImageResource();
        $sizeInfo = getimagesize($this->fullPath);
        $origWidth = (int) $sizeInfo[0];
        $origHeight = (int) $sizeInfo[1];

        if($origWidth > $origHeight) {
            // Wide image
            $newWidth = $width;
            $newHeight = round($origHeight * ($newWidth / $origWidth));
        }
        else {
            // Tall image
            $newHeight = $height;
            $newWidth = round($origWidth * ($newHeight / $origHeight));
        }
        $thumbnail = @imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        if($this->type === 'jpg') {
            return imagejpeg($thumbnail, $this->thumbnailPath, 90);
        }
        else if($this->type === 'png') {
            return imagepng($thumbnail, $this->thumbnailPath, 1);
        }
    }


    /**
     * Crops the image to the specified coordinates
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     * @return boolean
     */
    public function cropToDimensions($x, $y, $width, $height) {
        $image = $this->_getImageResource();
        $cropped = @imagecreatetruecolor($width, $height);
        imagecopy($cropped, $image, 0, 0, $x, $y, $width, $height);
        if($this->type === 'jpg') {
            return imagejpeg($cropped, $this->fullPath, 90);
        }
        else if($this->type === 'png') {
            return imagepng($cropped, $this->fullPath, 1);
        }
    }

    /**
     * Creates the appropriate GD image resource and returns to the caller
     * @return resource
     */
    private function _getImageResource() {
        if($this->type === 'jpg') {
            return @imagecreatefromjpeg($this->fullPath);
        }
        else if($this->type === 'png') {
            return @imagecreatefrompng($this->fullPath);
        }
        else {
            return false;
        }
    }

    /**
     * Validates image type based on the file extension
     * @param string $filePath
     * @return boolean
     */
    public static function IsValidImage($filePath) {
        return in_array(substr($filePath, -3), array('jpg', 'png'));
    }

    /**
     * Handle a standard POST form upload
     * @param string $formField Form field containing the image
     * @param string $imagesPath Path where image should be saved
     * @return a single Image object
     */
    public static function CreateFromUpload($formField, $imagesPath) {
        $fileData = $_FILES[$formField];
        if($fileData['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException("{$fileData['name']} failed to upload");
        }

        // Check for valid image types (PNG or JPG)
        $fileData['name'] = str_replace(' ', '_', strtolower($fileData['name']));
        $destination = $imagesPath . DS . $fileData['name'];
        if(self::IsValidImage($destination) && move_uploaded_file($fileData['tmp_name'], $destination)) {
            $image = new Image($fileData['name']);
            return $image;
        }
        else {
            throw new \RuntimeException("{$fileData['name']} failed to upload");
        }
    } // end CreateFromUpload()
} // end class Image

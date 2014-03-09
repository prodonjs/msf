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
     * Basename of thumbnail image file
     * @var string
     */
    public $thumbnailName;

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
        $this->fullPath = $filePath;
        $this->name = basename($this->fullPath);
        $this->sizeInBytes = filesize($this->fullPath);
        $this->type = substr($this->fullPath, -3);
        $this->thumbnailPath = dirname($this->fullPath) . DS . self::THUMBNAIL_PREFIX . $this->name;
        $this->thumbnailName = basename($this->thumbnailPath);
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
     * Validates image using extension and mime type
     * @param string $filePath
     * @return boolean
     */
    public static function IsValidImage($extension, $mimeType) {
        $validExtensions = array('jpg', 'png');
        $validMimeTypes = array('image/jpeg', 'image/pjpeg', 'image/png');

        return in_array($extension, $validExtensions) && in_array($mimeType, $validMimeTypes);
    }

    /**
     * Handle a standard POST form upload for a single image
     * @param string $name Uploaded file name from form
     * @param string $path Path where image should be saved
     * @param int $thumbnailWidth Width of thumbnail image
     * @param int $thumbnailHeight Height of thumbnail image
     * @return a single Image object
     */
    public static function CreateFromUpload($name, $path, $thumbnailWidth, $thumbnailHeight) {
        $fileData = $_FILES[$name];
        if($fileData['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException("{$fileData['name']} failed to upload");
        }

        // Check for valid image types (PNG or JPG)
        $fileData['name'] = str_replace(' ', '_', strtolower($fileData['name']));
        $destination = $path . DS . $fileData['name'];
        $extension = substr($destination, -3);
        $mimeType = $fileData['type'];
        if(self::IsValidImage($extension, $mimeType) && move_uploaded_file($fileData['tmp_name'], $destination)) {
            $image = new Image($destination);
            $image->generateThumbnail($thumbnailWidth, $thumbnailHeight);
            return $image;
        }
        else {
            throw new \RuntimeException("{$fileData['name']} failed to upload");
        }
    } // end CreateFromUpload()
} // end class Image

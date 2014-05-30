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
     * Scales the image to fit within the preferred dimensions
     * @param int $width
     * @param int $height
     * @return boolean
     */
    public function scaleImage($width, $height, $isThumbnail=true) {
        $image = $this->_getImageResource();
        list($newWidth, $newHeight, $origWidth, $origHeight) =
             $this->_getScaledDimensions($width, $height);

        $scaled = @imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($scaled, $image, 0, 0, 0, 0, $newWidth,
                         $newHeight, $origWidth, $origHeight);
        $fileName = $isThumbnail ? $this->thumbnailPath : $this->fullPath;
        if($this->type === 'jpg') {
            return imagejpeg($scaled, $fileName, 90);
        }
        else if($this->type === 'png') {
            return imagepng($scaled, $fileName, 1);
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
     * Accepts preferred width and height value and returns
     * an array with:
     * (new-scaled width, new-scaled height, orig width, orig height)
     * @param type $width
     * @param type $height
     * @return array
     */
    private function _getScaledDimensions($width, $height) {
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
        return array($newWidth, $newHeight, $origWidth, $origHeight);
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

        return in_array($extension, $validExtensions)
               && in_array($mimeType, $validMimeTypes);
    }

    /**
     * Handle a standard POST form upload for a single image
     * @param string $name Uploaded file name from form
     * @param string $path Path where image should be saved
     * @param array $dimensions [Width, Height, Thumbnail Width, Thumbnail Height]
     * @return a single Image object
     */
    public static function CreateFromUpload($name, $path, $dimensions) {
        $fileData = $_FILES[$name];
        if($fileData['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException("{$fileData['name']} failed to upload");
        }

        // Check for valid image types (PNG or JPG)
        $fileData['name'] = str_replace(' ', '_', strtolower($fileData['name']));
        $destination = $path . DS . $fileData['name'];
        $extension = substr($destination, -3);
        $mimeType = $fileData['type'];
        if(self::IsValidImage($extension, $mimeType)
           && move_uploaded_file($fileData['tmp_name'], $destination)) {
            $image = new Image($destination);
            // Scale the image and its thumbnail to the appropriate size
            $image->scaleImage($dimensions[0], $dimensions[1], false);
            $image->scaleImage($dimensions[2], $dimensions[3], true);
            return $image;
        }
        else {
            throw new \RuntimeException("{$fileData['name']} failed to upload");
        }
    } // end CreateFromUpload()
} // end class Image

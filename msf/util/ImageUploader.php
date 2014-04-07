<?php
namespace msf\util;

class ImageUploader extends \Slim\Middleware
{
    /**
     * Call
     *
     * Checks if any files designated to be Image objects were uploaded and handles
     * the process. Stores the Image object in the image property of app
     */
    public function call()
    {
        if(!empty($_FILES['image']['name'])) {
            try {
                $image = \msf\models\Image::CreateFromUpload(
                  'image', $this->app->config('imagesUploadPath'),
                  \msf\models\Property::THUMBNAIL_WIDTH,
                  \msf\models\Property::THUMBNAIL_HEIGHT
                );
                $this->app->image = $image;
            }
            catch (\RuntimeException $e) {
                $this->app->log->error("Failed to upload image {$e->getMessage()}");
            }
        }
        $this->next->call();
    } // end call()
}
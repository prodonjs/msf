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
                  'image',
                  $this->app->imageSettings['uploadPath'],
                  array(
                    $this->app->imageSettings['dimensions']['width'],
                    $this->app->imageSettings['dimensions']['height'],
                    $this->app->imageSettings['dimensions']['thumbnailWidth'],
                    $this->app->imageSettings['dimensions']['thumbnailHeight']
                  )
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

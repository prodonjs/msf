<?php
require_once('bootstrap.php');

$app = new \Slim\Slim();
$app->get('/admin/properties', function () {
    $properties = \msf\models\Property::FindAll(
        new \msf\models\FileDataSource(FILE_DATA_SOURCE_PATH)
    );
    print_r($properties);
    echo 'Hello, you are on the properties page';
});
$app->run();
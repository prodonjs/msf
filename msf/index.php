<?php
require_once 'bootstrap.php';

/* Configure application-wide settings */
$app = new \Slim\Slim(array(
    'templates.path' => TEMPLATES_PATH
));
$app->dataSource = new \msf\models\FileDataSource(FILE_DATA_SOURCE_PATH);
$images = array();
$images['uploadPath'] = $app->dataSource->getSubTypePath(\msf\models\FileDataSource::IMAGES);
$images['path'] = str_replace(SITE_ROOT, '', $images['uploadPath']);
$images['dimensions'] = array(
    'width' => \msf\models\Property::PREFERRED_IMG_WIDTH,
    'height' => \msf\models\Property::PREFERRED_IMG_HEIGHT,
    'thumbnailWidth' => \msf\models\Property::THUMBNAIL_WIDTH,
    'thumbnailHeight' => \msf\models\Property::THUMBNAIL_HEIGHT
);
$app->imageSettings = $images;

/* Create monolog logger and store logger in container as singleton */
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('MS&F Administrative Portal');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(
        LOGS_ROOT . DS . 'msf_' . date('Ym') . '.log', \Monolog\Logger::DEBUG
    ));
    return $log;
});

/* Protect admin routes via Basic Auth */
$app->add(new \msf\util\HttpBasicAuth(MSF_ADMIN_USER, MSF_ADMIN_PASS,
    'MS&F Administrators Portal', '#^/admin/#'
));
/* Add Image uploading */
$app->add(new \msf\util\ImageUploader());
/* Add SessionCookie for Flash messages */
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'msf',
    'secret' => 'f HiN!~HyD9C~~>4C.NN-L#]1,5k^=P=!,cL0XlzZ1F+-8Q<]rryA&}|TdRvr^ <',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('../cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

/**
 * Administrator Property requests
 */
$app->group('/admin/properties', function() use ($app) {
    /* Properties management console */
    $app->get('/', function() use ($app) {
        $properties = \msf\models\Property::FindAll($app->dataSource, 0, 'created', 'DESC');
        $app->log->info("Properties - View All");
        $app->render('admin_properties_index.twig', array(
            'pageTitle' => 'Properties Management Console',
            'properties' => $properties,
            'imageSettings' => $app->imageSettings,
            'addUrl' => $app->urlFor('admin_properties_add'),
            'editUrl' => $app->urlFor('admin_properties_edit'),
	    'deleteUrl' => $app->urlFor('admin_properties_delete')
        ));
    })->name('admin_properties_index');
    /* Create new Property form */
    $app->get('/add', function() use ($app) {
        $app->log->info("Properties - Create New");
        $app->render('admin_properties_add.twig', array(
            'pageTitle' => 'Add a New Property',
	    'propertyTypes' => \msf\models\Property::$validTypes,
            'indexUrl' => $app->urlFor('admin_properties_index')
        ));
    })->name('admin_properties_add');
    /* Create new Property post-handler */
    $app->post('/add', function() use ($app) {
        $data = $app->request->post();
        $property = new \msf\models\Property($app->dataSource);
        if(!isset($app->image)) {
            $message = "Unable to upload image for Property";
            $app->log->error($message);
            $app->flash('message', $message);
            $app->response->redirect($app->urlFor('admin_properties_index'));
        }
        $property->fromData($data['property']);
        $property->image = $app->image;
        if(!$property->save()) {
            $message = "Unable to save Property";
            $app->flash('message', $message);
            $app->log->error($message);
        }
        $message = "Property created";
        $app->flash('message', $message);
        $app->log->info("{$message} - ID: {$property->id}");
        $app->response->redirect($app->urlFor('admin_properties_edit', array('id' => $property->id)));
    });
    /* Edit Property form */
    $app->get('/edit/:id', function($id) use ($app) {
        try {
            $property = \msf\models\Property::Get($id, $app->dataSource);
            $app->render('admin_properties_edit.twig', array(
                'property' => $property,
                'pageTitle' => 'Edit Property',
                'propertyTypes' => \msf\models\Property::$validTypes,
                'editUrl' => $app->urlFor('admin_properties_edit', array('id' => $id)),
                'indexUrl' => $app->urlFor('admin_properties_index'),
                'imageSettings' => $app->imageSettings
            ));
        }
        catch (\RuntimeException $e) {
            $message = "Property does not exist - ID: {$id}";
            $app->flash('message', $message);
            $app->log->error($message);
            $app->response->redirect($app->urlFor('admin_properties_index'));
        }
    })->name('admin_properties_edit');
    /* Edit Property put-handler */
    $app->put('/edit/:id', function($id) use ($app) {
        try {
            $property = \msf\models\Property::Get($id, $app->dataSource);
        }
        catch (\RuntimeException $e) {
            $message = "Unable to save Property";
            $app->flash('message', $message);
            $app->log->error($message);
            $app->response->redirect($app->urlFor('admin_properties_index'));
        }
        $data = $app->request->put();
        $property->fromData($data['property']);
        /* Check for image replacement */
        if(isset($app->image)) {
            $property->image = $app->image;
            $app->log->info("Property Image Replaced with {$property->image->name} - ID: {$property->id}");
        }
        /* Check for image crop */
        else if (!empty($data['image']['dimensions'])) {
            $dimensions = explode(',', $data['image']['dimensions']);
            $property->image->cropToDimensions($dimensions[0], $dimensions[1], $dimensions[2], $dimensions[3]);
            $property->image->generateThumbnail(
                $app->imageSettings['dimensions']['thumbnailWidth'], $app->imageSettings['dimensions']['thumbnailHeight']
            );
            $app->log->info("Property Image Cropped - ID: {$property->id}");
        }

        if(!$property->save()) {
            $message = "Unable to save Property";
            $app->flash('message', $message);
            $app->log->error($message);
            $app->response->redirect($app->urlFor('admin_properties_index'));
        }

        $message = "Property Saved";
        $app->flash('message', $message);
        $app->log->info("{$message} - ID: {$property->id}");
        $app->response->redirect($app->urlFor('admin_properties_edit', array('id' => $id)));
    });
    /* Delete a property */
    $app->delete('/delete/:id', function ($id) use ($app) {
        try {
            $property = \msf\models\Property::Get($id, $app->dataSource);
            if($property->delete()) {
                $app->log->info("Property deleted - ID: {$id}");
            }
            else {
                $app->log->error("Unable to delete Property - ID: {$id}");
            }
        }
        catch (\RuntimeException $e) {
            $app->log->error("Property does not exist - ID: {$id}");
        }
        $app->response->redirect($app->urlFor('admin_properties_index'));
    })->name('admin_properties_delete');
});

/**
 * Non-Admin protected functions
 */
$app->get('/properties/recent/:number', function($number) use ($app) {
    $properties = \msf\models\Property::FindAll($app->dataSource, $number, 'created', 'DESC');
    $app->etag(md5($number . json_encode($properties)));
    $app->log->info("Properties - Recent ({$number})");
    /* If number is 3, use the slider template, otherwise use the recent template */
    $template = $number == 3 ? 'properties_slider.twig' : 'properties_recent.twig';
    $app->render($template, array(
        'properties' => $properties,
        'imageSettings' => $app->imageSettings
    ));
})->conditions(array('number' => '\d+'))->name('properties_recent');

// Run app
$app->run();

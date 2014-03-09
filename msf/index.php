<?php
require_once 'bootstrap.php';

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => TEMPLATES_PATH,
    'datasource' => new \msf\models\FileDataSource(FILE_DATA_SOURCE_PATH),
    'imagesPath' => IMAGES_SRC
));

// Create monolog logger and store logger in container as singleton
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('MS&F Administrative Portal');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(
        LOGS_ROOT . DS . 'msf_' . date('Ym') . '.log', \Monolog\Logger::DEBUG
    ));
    return $log;
});

/* Use Basic Authentication Middleware */
#$app->add(new \utility\HttpBasicAuth('Powerlane Integration System'));

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
        $properties = \msf\models\Property::FindAll($app->config('datasource'), 0, 'created', 'DESC');
        $app->log->info("Properties - View All");
        $app->render('admin_properties_index.twig', array(
            'pageTitle' => 'Properties Management Console',
            'properties' => $properties,
            'imagesPath' => $app->config('imagesPath'),
            'addUrl' => $app->urlFor('admin_properties_add')
        ));
    })->name('admin_properties_index');
    /* Create new Property form */
    $app->get('/add', function() use ($app) {
        $app->log->info("Properties - Create New");
        $app->render('admin_properties_add.twig', array(
            'pageTitle' => 'Add a New Property',
            'indexUrl' => $app->urlFor('admin_properties_index')
        ));
    })->name('admin_properties_add');
    /* Create new Property post-handler */
    $app->post('/add', function() use ($app) {
        $data = $app->request->post();
        $property = new \msf\models\Property($app->config('datasource'));
        if(!empty($_FILES['image'])) {
            try {
                $image = \msf\models\Image::CreateFromUpload(
                  'image', IMAGES_PATH,
                  \msf\models\Property::THUMBNAIL_WIDTH,
                  \msf\models\Property::THUMBNAIL_HEIGHT
                );
                $property->image = $image;
            }
            catch (\RuntimeException $e) {
                $app->log->error("Property Add - Failed to upload image {$e->getMessage()}");
                $app->response->redirect($app->urlFor('admin_properties_add'));
            }
        }
        $property->fromData($data['property']);
        if($property->save()) {
            $app->log->info("Property created - ID: {$property->id}");
            $app->response->redirect($app->urlFor('admin_properties_index'));
        }
        else {
            $app->log->error("Unable to save Property");
        }
    });
    /* Edit Property form */
    $app->get('/edit/:id', function($id) use ($app) {
        try {
            $property = \msf\models\Property::Get($id, $app->config('datasource'));
            $app->log->info("Properties - Edit ID: {$id}");
            $app->render('admin_properties_edit.twig', array(
                'property' => $property,
                'pageTitle' => 'Edit Property',
                'editUrl' => $app->urlFor('admin_properties_edit', array('id' => $id)),
                'indexUrl' => $app->urlFor('admin_properties_index'),
                'imagesPath' => $app->config('imagesPath'),
            ));
        }
        catch (RuntimeException $e) {
            $app->log->error("Property does not exist - ID: {$id}");
            $app->response->redirect($app->urlFor('admin_properties_index'));
        }
    })->name('admin_properties_edit');
    /* Edit Property put-handler */
    $app->put('/edit/:id', function($id) use ($app) {
        try {
            $property = \msf\models\Property::Get($id, $app->config('datasource'));
        }
        catch (RuntimeException $e) {
            $app->log->error("Property does not exist - ID: {$id}");
            $app->response->redirect($app->urlFor('admin_properties_index'));
        }
        $data = $app->request->put();
        if(!empty($_FILES['image'])) {
            try {
                $image = \msf\models\Image::CreateFromUpload(
                  'image', IMAGES_PATH,
                  \msf\models\Property::THUMBNAIL_WIDTH,
                  \msf\models\Property::THUMBNAIL_HEIGHT
                );
                $property->image = $image;
            }
            catch (\RuntimeException $e) {
                $app->log->error("Property Add - Failed to upload image {$e->getMessage()}");
                $app->response->redirect($app->urlFor('admin_properties_add'));
            }
        }
        $property->fromData($data['property']);
        if($property->save()) {
            $app->log->info("Property saved - ID: {$property->id}");
        }
        else {
            $app->log->error("Unable to save Property");
        }
        $app->response->redirect($app->urlFor('admin_properties_edit', array('id' => $id)));
    });
    /* Delete a property */
    $app->delete('/delete/:id', function ($id) use ($app) {
        try {
            $property = \msf\models\Property::Get($id, $app->config('datasource'));
            if($property->delete()) {
                $app->log->info("Property deleted - ID: {$id}");
            }
            else {
                $app->log->error("Unable to delete Property - ID: {$id}");
            }
        }
        catch (RuntimeException $e) {
            $app->log->error("Property does not exist - ID: {$id}");
        }
        $app->response->redirect($app->urlFor('admin_properties_index'));
    });
});

// Run app
$app->run();

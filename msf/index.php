<?php
require_once 'bootstrap.php';

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => TEMPLATES_PATH,
    'datasource' => new \msf\models\FileDataSource(FILE_DATA_SOURCE_PATH)
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
            'properties' => $properties
        ));
    });
    /* Create new Property form */
    $app->get('/add', function() use ($app) {
        $app->log->info("Properties - Create New");
        $app->render('admin_properties_add.twig', array(
            'pageTitle' => 'Add a New Property'
        ));
    });
    /* Create new Property Post */
    $app->post('/add', function() use ($app) {
        $data = $app->request->post();
        $property = new \msf\models\Property($app->config('datasource'));
        if(isset($data['property']['image'])) {
            try {
                $image = \msf\models\Image::CreateFromUpload('property[image]', IMAGES_PATH);
                print_r($image);
                $property->image = $image;
            }
            catch (\RuntimeException $e) {
                $app->log->error('Property Add - Failed to upload image');
                $app->log->error($e->getMessage());
            }
        }
        $property->fromData($data['property']);
        if($property->save()) {
            $app->log->info("Property created - ID: {$property->id}");        }
        else {
            $app->log->error("Unable to save Property");
        }
    });
});

// Run app
$app->run();

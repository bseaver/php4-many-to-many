<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/Brand.php';
    require_once __DIR__.'/../src/Store.php';
    require_once __DIR__.'/../src/BrandStore.php';
    require_once __DIR__.'/AppRender.php';

    $app = new Silex\Application();
    $app['debug'] = true;

    $server = 'mysql:host=localhost:8889;dbname=shoes';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    // Home route (send to Store entry edit)

    $app->get('/', function() use ($app) {
        return AppRender::editStores($app);
    });

    // Store CRUD routes

    $app->post('/post/store', function() use ($app) {
        return AppRender::postStore($app, $_POST['name']);
    });

    $app->get('/get/stores', function() use ($app) {
        return AppRender::editStores($app);
    });

    $app->get('/get/store/{id}/edit', function($id) use ($app) {
        return AppRender::editStore($app, $id);
    });

    $app->patch('/patch/store', function() use ($app) {
        return AppRender::updateStore($app, $_POST['name'], $_POST['id']);
    });

    $app->delete('/delete/store/{id}', function($id) use ($app) {
        return AppRender::deleteStore($app, $id);
    });

    $app->delete('/delete/stores', function() use ($app) {
        return 'To Do';
    });

    // Store / Brand Associations

    $app->get('/get/store/{id}/brands', function() use ($app) {
        return 'To Do';
    });

    // Brand CRUD routes

    $app->get('/get/brands', function() use ($app) {
        return 'To Do';
    });


    return $app;
?>

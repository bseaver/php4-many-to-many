<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/Brand.php';
    require_once __DIR__.'/../src/Store.php';
    require_once __DIR__.'/../src/BrandStore.php';

    session_start();
    define('NEXT_VIEW', 'next_view');
    define('NEXT_VIEW_CONTEXT', 'next_view_context');
    // if (empty($_SESSION[LIST_OF_CONTACTS])) {
    //     $_SESSION[LIST_OF_CONTACTS] = array();
    // }

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


    $app->get('/', function() use ($app) {
        if (empty($_SESSION['NEXT_VIEW'])) {
            $next_view = 'edit.html.twig';
            $next_view_context = array(
                    'edit_store' => New Store,
                    'edit_brand' => New Brand,
                    'list_header' => 'All Stores',
                    'items' => Store::getAll(),
                    'related_entities' => 'Brands',
                    'this_entity' => 'store'
            );
        }

        return $app['twig']->render($next_view, $next_view_context);
    });

    $app->post('/post/store', function() use ($app) {
        return 'To Do';
    });

    $app->post('/post/brand', function() use ($app) {
        return 'To Do';
    });

    $app->patch('/patch/store', function() use ($app) {
        return 'To Do';
    });

    $app->patch('/patch/brand', function() use ($app) {
        return 'To Do';
    });

    $app->delete('/delete/store', function() use ($app) {
        return 'To Do';
    });

    $app->delete('/delete/brand', function() use ($app) {
        return 'To Do';
    });

    $app->delete('/delete/stores', function() use ($app) {
        return 'To Do';
    });

    $app->delete('/delete/brands', function() use ($app) {
        return 'To Do';
    });

    $app->get('/get/store/{id}', function($id) use ($app) {
        return 'To Do';
    });

    $app->get('/get/brand/{id}', function($id) use ($app) {
        return 'To Do';
    });

    $app->get('/get/stores', function() use ($app) {
        return 'To Do';
    });

    $app->get('/get/brands', function() use ($app) {
        return 'To Do';
    });

    $app->get('/get/store/{id}/edit', function($id) use ($app) {
        return 'To Do';
    });

    $app->get('/get/brand/{id}/edit', function($id) use ($app) {
        return 'To Do';
    });

    $app->post('/post/store/{store_id}/brand/{brand_id}', function($store_id, $brand_id) use ($app) {
        return 'To Do';
    });

    $app->post('/post/brand/{brand_id}/store/{store_id}', function($store_id, $brand_id) use ($app) {
        return 'To Do';
    });

    $app->delete('/delete/store/{store_id}/brand/{brand_id}', function($store_id, $brand_id) use ($app) {
        return 'To Do';
    });

    $app->delete('/delete/brand/{brand_id}/store/{store_id}', function($brand_id, $store_id) use ($app) {
        return 'To Do';
    });


    return $app;
?>

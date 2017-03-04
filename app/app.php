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
        return $app->redirect('/get/stores/edit');
    });

    $app->get('/get/stores/edit', function() use ($app) {
        $next_view = 'edit.html.twig';
        $next_view_context = array(
                'edit_store' => New Store,
                'edit_brand' => New Brand,
                'list_header' => 'All Stores',
                'items' => Store::getAll(),
                'related_entities' => 'Brands',
                'this_entity' => 'store'
        );

        if (!empty($_SESSION[NEXT_VIEW_CONTEXT])) {
            $next_view_context = array_merge(
                $next_view_context,
                $_SESSION[NEXT_VIEW_CONTEXT]
            );
            unset($_SESSION[NEXT_VIEW_CONTEXT]);
        }

        return $app['twig']->render($next_view, $next_view_context);
    });

    $app->post('/post/store', function() use ($app) {
        $store = new Store($_POST['name']);
        $store->save();
        $_SESSION['NEXT_VIEW_CONTEXT'] = array(
            'list_header' => 'New Store',
            'items' => [$store]
        );
        return $app->redirect('/get/stores/edit');
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

    $app->delete('/delete/store/{id}', function($id) use ($app) {
        BrandStore::deleteSome('store_id', $id);
        Store::deleteSome('id', $id);
        return $app->redirect('/get/stores/edit');
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

    // $app->get('/get/store/{id}', function($id) use ($app) {
    //     return 'To Do';
    // });

    $app->get('/get/brand/{id}', function($id) use ($app) {
        return 'To Do';
    });

    // $app->get('/get/stores', function() use ($app) {
    //     $next_view = 'edit.html.twig';
    //     $next_view_context = array(
    //             'edit_store' => New Store,
    //             'edit_brand' => New Brand,
    //             'list_header' => 'All Stores',
    //             'items' => Store::getAll(),
    //             'related_entities' => 'Brands',
    //             'this_entity' => 'store'
    //     );
    //
    //     return $app['twig']->render($next_view, $next_view_context);
    // });

    $app->get('/get/brands', function() use ($app) {
        return 'To Do';
    });

    $app->get('/get/store/{id}/edit', function($id) use ($app) {
        $_SESSION['NEXT_VIEW_CONTEXT'] = array('edit_store' => Store::find($id));
        return $app->redirect('/get/stores/edit');
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

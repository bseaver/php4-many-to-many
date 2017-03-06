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
        return AppRender::editBrandsStores('store', $app);
    });

    // Store CRUD routes

    $app->post('/post/store', function() use ($app) {
        return AppRender::postBrandStore('store', $app, $_POST['name']);
    });

    $app->get('/get/stores', function() use ($app) {
        return AppRender::editBrandsStores('store', $app);
    });

    $app->get('/get/store/{id}/edit', function($id) use ($app) {
        return AppRender::editBrandStore('store', $app, $id);
    });

    $app->patch('/patch/store', function() use ($app) {
        return AppRender::updateBrandStore('store', $app, $_POST['name'], $_POST['id']);
    });

    $app->delete('/delete/store/{id}', function($id) use ($app) {
        return AppRender::deleteBrandStore('store', $app, $id);
    });

    $app->delete('/delete/stores', function() use ($app) {
        return AppRender::deleteBrandsStores('store', $app);
    });


    // Brand CRUD routes

    $app->post('/post/brand', function() use ($app) {
        return AppRender::postBrandStore('brand', $app, $_POST['name']);
    });

    $app->get('/get/brands', function() use ($app) {
        return AppRender::editBrandsStores('brand', $app);
    });

    $app->get('/get/brand/{id}/edit', function($id) use ($app) {
        return AppRender::editBrandStore('brand', $app, $id);
    });

    $app->patch('/patch/brand', function() use ($app) {
        return AppRender::updateBrandStore('brand', $app, $_POST['name'], $_POST['id']);
    });

    $app->delete('/delete/brand/{id}', function($id) use ($app) {
        return AppRender::deleteBrandStore('brand', $app, $id);
    });

    $app->delete('/delete/brands', function() use ($app) {
        return AppRender::deleteBrandsStores('brand', $app);
    });

    // Store / Brand Associations

    $app->get('/get/store/{id}/brands', function($id) use ($app) {
        return 'To Do';
    });

    $app->post('/post/store/{store_id}/brand/{brand_id}', function($store_id, $brand_id) use ($app) {
        return 'To Do';
    });

    $app->delete('/delete/store/{store_id}/brand/{brand_id}', function($store_id, $brand_id) use ($app) {
        return 'To Do';
    });

    $app->delete('/delete/store/{store_id}/brands', function($store_id) use ($app) {
        return 'To Do';
    });

    $app->delete('/delete/stores/brands', function() use ($app) {
        return 'To Do';
    });

    // Brand / Store Associations

    $app->get('/get/brand/{id}/stores', function() use ($app) {
        return 'To Do';
    });







    return $app;
?>

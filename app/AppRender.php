<?php
Class AppRender
{
    static function editStores(&$app, &$next_view_data_overrides = null)
    {
        $next_view = 'edit.html.twig';
        $next_view_data = array(
                'edit_store' => New Store,
                'edit_brand' => New Brand,
                'list_header' => 'All Stores',
                'items' => Store::getAll(),
                'related_entity_name' => 'Brand',
                'this_entity' => 'store'
        );

        if ($next_view_data_overrides) {
            $next_view_data = array_merge($next_view_data, $next_view_data_overrides);
        }

        return $app['twig']->render($next_view, $next_view_data);
    }


    static function postStore(&$app, $name)
    {
        $next_view_data_overrides = array();
        $name = trim($name);
        if ($name) {
            $store = new Store($name);
            $store->save();
            $next_view_data_overrides = array(
                'list_header' => 'New Store',
                'items' => [$store]
            );
        }
        return self::editStores($app, $next_view_data_overrides);
    }

    static function deleteStore(&$app, $id)
    {
        BrandStore::deleteSome('store_id', $id);
        Store::deleteSome('id', $id);
        return self::editStores($app);
    }
}
?>

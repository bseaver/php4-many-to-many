<?php
Class AppRender
{
    static function editStores(&$app, &$next_view_data_overrides = null)
    {
        $next_view = 'store_edit.html.twig';
        $next_view_data = array(
                'edit_store' => new Store,
                'crud_header' => '',
                'crud_items' => array(new Store),
                'list_header' => 'All Stores',
                'items' => Store::getAll(),
                'related_entity_name' => 'Brand',
                'related_entities' => 'brands',
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
            $stores_of_same_name = Store::getSome('name', $name);
            if (count($stores_of_same_name)) {
                $next_view_data_overrides = array(
                    'crud_header' => 'Store Already On File',
                    'crud_items' => $stores_of_same_name
                );
            } else {
                $store = new Store($name);
                $store->save();
                $next_view_data_overrides = array(
                    'crud_header' => 'New Store',
                    'crud_items' => [$store]
                );
            }
        }
        return self::editStores($app, $next_view_data_overrides);
    }

    static function editStore(&$app, $id)
    {
        $store = Store::find($id);
        $next_view_data_overrides = array(
            'edit_store' => $store
        );
        return self::editStores($app, $next_view_data_overrides);
    }

    static function updateStore(&$app, $name, $id)
    {
        $next_view_data_overrides = array();
        $name = trim($name);
        $done = !$name;
        $dups = 0;

        if (!$done) {
            $store = Store::find($id);
            $stores_of_same_name = Store::getSome('name', $name);
            $dups = count($stores_of_same_name);
        }

        if (!$done && !$dups) {
            $store->update($name);
            $next_view_data_overrides = array(
                'crud_header' => 'Updated Store',
                'crud_items' => [$store]
            );
            $done = true;
        }

        if (!$done && $dups == 1) {
            $done = $store->getId() == $stores_of_same_name[0]->getId();
        }

        if (!$done && $dups) {
            $next_view_data_overrides = array(
                'crud_header' => 'Store Already On File',
                'crud_items' => $stores_of_same_name,
                'edit_store' => $store
            );
            $done = true;
        }
        return self::editStores($app, $next_view_data_overrides);
    }

    static function deleteStore(&$app, $id)
    {
        $store = Store::find($id);
        $store->delete();
        $store->setId(0);
        BrandStore::deleteSome('store_id', $id);
        $next_view_data_overrides = array(
            'crud_header' => 'Deleted!',
            'crud_items' => [$store]
        );
        return self::editStores($app, $next_view_data_overrides);
    }
}
?>

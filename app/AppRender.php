<?php
Class AppRender
{
    static function relatedContext($context)
    {
        return $context == 'brand' ? 'store' : 'brand';
    }

    static function primaryObject($context, $related = false)
    {
        if ($related) {$context = self::relatedContext($context);}
        return $context == 'brand' ? 'Brand' : 'Store';
    }

    static function pluralUpperCaseName($context, $related = false)
    {
        if ($related) {$context = self::relatedContext($context);}
        return $context == 'brand' ? 'Brands' : 'Stores';
    }

    static function singularUpperCaseName($context, $related = false)
    {
        if ($related) {$context = self::relatedContext($context);}
        return $context == 'brand' ? 'Brand' : 'Store';
    }

    static function entities($context, $related = false)
    {
        if ($related) {$context = self::relatedContext($context);}
        return $context == 'brand' ? 'brands' : 'stores';
    }

    static function entity($context, $related = false)
    {
        if ($related) {$context = self::relatedContext($context);}
        return $context == 'brand' ? 'brand' : 'store';
    }

    static function brandStoreId($context)
    {
        return $context == 'brand' ? 'brand_id' : 'store_id';
    }

    static function brandsStoresHome(&$app)
    {
        // Search for a store - if found edit that one's links
        // Otherwise send the user to the edit stores page
        $context = 'store';
        $id = null;

        $stores = Store::getSome('all');
        if (count($stores)) {
            $id = $stores[0]->getId();
        }

        if ($id) {
            return self::editBrandsStoresLinks('store', $app, $id);
        } else {
            return self::editBrandsStores('store', $app);
        }
    }

    static function editBrandsStoresLinks($context, &$app, $id)
    {
        $this_item = Store::getSome('id', $id)[0];

        $assoc_related_items = Brand::getSome('store_id', $id);
        $unassoc_related_items = Brand::getSome('null_store_id', $id);


        $next_view = 'assoc_edit.html.twig';
        $next_view_data = array(
            'this_item' => $this_item,
            'assoc_related_items' => $assoc_related_items,
            'unassoc_related_items' => $unassoc_related_items,
            'this_entity' => 'store',
            'this_entities' => 'stores',
            'this_entity_name' => 'Store',
            'this_entity_names' => 'Stores',
            'related_entity' => 'brand',
            'related_entities' => 'brands',
            'related_entity_name' => 'Brand',
            'related_entity_names' => 'Brands',
        );

        return $app['twig']->render($next_view, $next_view_data);
    }

    static function postBrandStoreLink($context, &$app, $entity_id, $related_entity_id)
    {
        $brand_id = $related_entity_id;
        $store_id = $entity_id;
        $brand_store = new BrandStore($brand_id, $store_id);
        $brand_store->save();

        return self::editBrandsStoresLinks($context, $app, $entity_id);
    }

    static function postBrandStoreName($context, &$app)
    {
        $this_id = $_POST['this_id'];
        $related_name = trim($_POST['related_name']);
        $done = !$related_name;
        $matched = 0;

        if (!$done) {
            $related_object = self::primaryObject($context, true);
            $object_getSome_method = array($related_object, 'getSome');
            $objects_of_same_name = $object_getSome_method('name', $related_name);
            $matched = count($objects_of_same_name);
        }

        if (!$done && $matched) {
            $related_item = $objects_of_same_name[0];
        }

        if (!$done && !$matched) {
            $related_item = new Brand($related_name);
            $related_item->save();
        }

        if (!$done){
            if ($context == 'store'){
                $brand_id = $related_item->getId();
                $store_id = $this_id;
            } else {
                $brand_id = $this_id;
                $store_id = $related_item->getId();
            }

            $brand_store = new BrandStore($brand_id, $store_id);
            $brand_store->save();
        }
        return self::editBrandsStoresLinks($context, $app, $this_id);
    }

    static function deleteBrandStoreLink($context, &$app, $brand_id, $store_id)
    {
        BrandStore::deleteSome('brand_and_store_ids', $brand_id, $store_id);

        return self::editBrandsStoresLinks($context, $app, $store_id);
    }

    static function deleteBrandStoreLinks($context, &$app, $entity_id)
    {
        $ids_to_delete = self::brandStoreId('context');

        BrandStore::deleteSome($ids_to_delete, $entity_id);

        return self::editBrandsStoresLinks($context, $app, $entity_id);
    }

    static function deleteBrandStoreLinksAll(&$app)
    {
        BrandStore::deleteSome('all');

        return self::brandsStoresHome($app);
    }

    static function editBrandsStores($context, &$app, &$next_view_data_overrides = null)
    {
        $primary_object = self::primaryObject($context);
        $empty_primary_object = new $primary_object;

        $list_header = 'All ' . self::pluralUpperCaseName($context);

        $object_getAll_method = array($primary_object, 'getAll');
        $items = $object_getAll_method();

        $related_entity_name = self::singularUpperCaseName($context, true);

        $related_entities = self::entities($context, true);

        $this_entity = self::entity($context);
        $this_entities = self::entities($context);
        $this_entity_name = self::singularUpperCaseName($context);
        $this_entity_names = self::pluralUpperCaseName($context);

        $next_view = 'maint_edit.html.twig';
        $next_view_data = array(
                'edit_item' => $empty_primary_object,
                'crud_header' => '',
                'crud_items' => array($empty_primary_object),
                'list_header' => $list_header,
                'items' => $items,
                'related_entity_name' => $related_entity_name,
                'related_entities' => $related_entities,
                'this_entity' => $this_entity,
                'this_entities' => $this_entities,
                'this_entity_name' => $this_entity_name,
                'this_entity_names' => $this_entity_names
        );

        if ($next_view_data_overrides) {
            $next_view_data = array_merge($next_view_data, $next_view_data_overrides);
        }

        return $app['twig']->render($next_view, $next_view_data);
    }

    static function duplicate_object_by_name($context, $this_object, $name, &$next_view_data_overrides)
    {
        $primary_object = self::primaryObject($context);
        $object_getSome_method = array($primary_object, 'getSome');
        $objects_of_same_name = $object_getSome_method('name', $name);
        $entity_name = self::singularUpperCaseName($context);

        $dups = count($objects_of_same_name);
        $done = false;



        if (!$done && $dups == 1) {
            $done = $this_object->getId() == $objects_of_same_name[0]->getId();

            if ($done) {
                $crud_header = $entity_name . ' Unchanged';
                $next_view_data_overrides = array(
                    'crud_header' => $crud_header,
                    'crud_items' => [$this_object]
                );
            }
        }

        if (!$done && $dups) {
            $crud_header = $entity_name . ' Already On File';
            $next_view_data_overrides = array(
                'crud_header' => $crud_header,
                'crud_items' => $objects_of_same_name,
                'edit_item' => $this_object
            );
            $done = true;
        }

        return $dups;
    }

    static function postBrandStore($context, &$app, $name)
    {
        $primary_object = self::primaryObject($context);
        $entity_name = self::singularUpperCaseName($context);
        $next_view_data_overrides = array();
        $name = trim($name);
        $done = !$name;
        $dups = 0;

        if (!$done) {
            $new_object = new $primary_object($name);
            $dups = self::duplicate_object_by_name($context, $new_object, $name, $next_view_data_overrides);
        }

        if (!$done && !$dups) {
            $object_save_method = array($new_object, 'save');
            $object_save_method();
            $crud_header = $entity_name . ' Saved';
            $next_view_data_overrides = array(
                'crud_header' => $crud_header,
                'crud_items' => [$new_object]
            );
            $done = true;
        }

        return self::editBrandsStores($context, $app, $next_view_data_overrides);
    }

    static function editBrandStore($context, &$app, $id)
    {
        $primary_object = self::primaryObject($context);
        $object_find_method = array($primary_object, 'find');
        $object = $object_find_method($id);
        $next_view_data_overrides = array(
            'edit_item' => $object
        );
        return self::editBrandsStores($context, $app, $next_view_data_overrides);
    }

    static function updateBrandStore($context, &$app, $name, $id)
    {
        $primary_object = self::primaryObject($context);
        $entity_name = self::singularUpperCaseName($context);
        $next_view_data_overrides = array();
        $name = trim($name);
        $done = !$name;
        $dups = 0;

        if (!$done) {
            $this_object = $primary_object::find($id);
            $dups = self::duplicate_object_by_name($context, $this_object, $name, $next_view_data_overrides);
        }

        if (!$done && !$dups) {
            $this_object->update($name);
            $crud_header = $entity_name . ' Updated';
            $next_view_data_overrides = array(
                'crud_header' => $crud_header,
                'crud_items' => [$this_object]
            );
            $done = true;
        }

        return self::editBrandsStores($context, $app, $next_view_data_overrides);
    }

    static function deleteBrandStore($context, &$app, $id)
    {
        $primary_object = self::primaryObject($context);
        $join_table_id = self::brandStoreId($context);
        $entity_name = self::singularUpperCaseName($context);

        $this_object = $primary_object::find($id);
        $this_object->delete();
        $this_object->setId(0); // Signals List View to Disable buttons
        BrandStore::deleteSome($join_table_id, $id);

        $crud_header = $entity_name . ' Deleted';
        $next_view_data_overrides = array(
            'crud_header' => $crud_header,
            'crud_items' => [$this_object]
        );
        return self::editBrandsStores($context, $app, $next_view_data_overrides);
    }

    static function deleteBrandsStores($context, &$app)
    {
        $primary_object = self::primaryObject($context);
        $object_deleteAll_method = array($primary_object, 'deleteAll');
        $object_deleteAll_method();
        BrandStore::deleteAll();
        return self::editBrandsStores($context, $app);
    }
}
?>

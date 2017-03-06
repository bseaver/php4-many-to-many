<?php
Class AppRender
{
    static function primaryObject($context)
    {
        return $context == 'brand' ? 'Brand' : 'Store';
    }

    static function pluralUpperCaseName($context)
    {
        return $context == 'brand' ? 'Brands' : 'Stores';
    }

    static function relatedEntitylUpperCaseName($context)
    {
        return $context == 'brand' ? 'Store' : 'Brand';
    }

    static function relatedEntities($context)
    {
        return $context == 'brand' ? 'stores' : 'brands';
    }

    static function thisEntity($context)
    {
        return $context == 'brand' ? 'brand' : 'store';
    }

    static function singlularUpperCaseName($context)
    {
        return $context == 'brand' ? 'Brand' : 'Store';
    }

    static function brandStoreDeleteSomeId($context)
    {
        return $context == 'brand' ? 'brand_id' : 'store_id';
    }


    static function editBrandsStores($context, &$app, &$next_view_data_overrides = null)
    {
        $primary_object = self::primaryObject($context);
        $empty_primary_object = new $primary_object;

        $list_header = 'All ' . self::pluralUpperCaseName($context);

        $object_getAll_method = array($primary_object, 'getAll');
        $items = $object_getAll_method();

        $related_entity_name = self::relatedEntitylUpperCaseName($context);

        $related_entities = self::relatedEntities($context);

        $this_entity = self::thisEntity($context);


        $next_view = 'store_edit.html.twig';
        $next_view_data = array(
                'edit_store' => $empty_primary_object,
                'crud_header' => '',
                'crud_items' => array($empty_primary_object),
                'list_header' => $list_header,
                'items' => $items,
                'related_entity_name' => $related_entity_name,
                'related_entities' => $related_entities,
                'this_entity' => $this_entity
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
        $entity_name = self::singlularUpperCaseName($context);

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
                'edit_store' => $this_object
            );
            $done = true;
        }

        return $dups;
    }

    static function postBrandStore($context, &$app, $name)
    {
        $primary_object = self::primaryObject($context);
        $entity_name = self::singlularUpperCaseName($context);
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
            'edit_store' => $object
        );
        return self::editBrandsStores($context, $app, $next_view_data_overrides);
    }

    static function updateBrandStore($context, &$app, $name, $id)
    {
        $primary_object = self::primaryObject($context);
        $entity_name = self::singlularUpperCaseName($context);
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
        $join_table_id = self::brandStoreDeleteSomeId($context);
        $entity_name = self::singlularUpperCaseName($context);

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

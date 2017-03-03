<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/BrandStore.php";

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);


    class BrandStoreTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            BrandStore::deleteAll();
        }

        function test_BrandStore_get_set_construct()
        {
            // Arrange
            $brand_store1 = new BrandStore(2, 1);

            // Act
            $brand_store2 = new BrandStore(3, 7);
            $brand_store2->setBrandId($brand_store1->getBrandId());
            $brand_store2->setStoreId($brand_store1->getStoreId());

            // Assert
            $this->assertEquals(
                [2, 1],
                [$brand_store2->getBrandId(), $brand_store2->getStoreId()]
            );
        }

        function test_BrandStore_save_deleteAll_getAll()
        {
            // Arrange
            $brand_store1 = new BrandStore(2, 1);
            $brand_store2 = new BrandStore(3, 7);

            // Act
            $brand_store1->save();
            $brand_store2->save();

            BrandStore::deleteAll();

            $brand_store3 = new BrandStore(11, 22);
            $brand_store4 = new BrandStore(14, 33);
            $brand_store3->save();
            $brand_store4->save();

            // Assert
            $this->assertEquals(
                [$brand_store3, $brand_store4],
                BrandStore::getAll()
            );
        }

        function test_BrandStore_update()
        {
        }

        function test_BrandStore_delete()
        {
        }

        function test_BrandStore_deleteSome_brand_id()
        {
        }

        function test_BrandStore_deleteSome_store_id()
        {
        }

        function test_BrandStore_find()
        {
        }

        function test_BrandStore_getSome_brand_id()
        {
        }

        function test_BrandStore_getSome_store_id()
        {
        }
    }
?>

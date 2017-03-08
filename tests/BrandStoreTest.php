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
            // Arrange
            $brand_store1 = new BrandStore(2, 1);
            $brand_store1->save();

            // Act
            $brand_store1->update(33, 37);
            $brand_stores = BrandStore::getAll();
            $brand_store2 = $brand_stores[0];

            // Assert
            $this->assertEquals(
                [33, 37],
                [$brand_store2->getBrandId(), $brand_store2->getStoreId()]
            );
        }

        function test_BrandStore_delete()
        {
            // Arrange
            $brand_store1 = new BrandStore(2, 1);
            $brand_store2 = new BrandStore(3, 7);
            $brand_store3 = new BrandStore(13, 17);
            $brand_store1->save();
            $brand_store2->save();
            $brand_store3->save();

            // Act
            $brand_store2->delete();

            // Assert
            $this->assertEquals(
                [$brand_store1, $brand_store3],
                BrandStore::getAll()
            );
        }

        function test_BrandStore_deleteSome_brand_id()
        {
            // Arrange
            $brand_store1 = new BrandStore(2, 1);
            $brand_store2 = new BrandStore(3, 17);
            $brand_store3 = new BrandStore(2, 7);
            $brand_store4 = new BrandStore(13, 17);
            $brand_store1->save();
            $brand_store2->save();
            $brand_store3->save();
            $brand_store4->save();

            // Act
            BrandStore::deleteSome('brand_id', 2);

            // Assert
            $this->assertEquals(
                [$brand_store2, $brand_store4],
                BrandStore::getAll()
            );
        }

        function test_BrandStore_deleteSome_store_id()
        {
            // Arrange
            $brand_store1 = new BrandStore(2, 1);
            $brand_store2 = new BrandStore(3, 17);
            $brand_store3 = new BrandStore(2, 7);
            $brand_store4 = new BrandStore(13, 17);
            $brand_store1->save();
            $brand_store2->save();
            $brand_store3->save();
            $brand_store4->save();

            // Act
            BrandStore::deleteSome('store_id', 17);

            // Assert
            $this->assertEquals(
                [$brand_store1, $brand_store3],
                BrandStore::getAll()
            );
        }

        function test_BrandStore_deleteSome_brand_and_store_ids()
        {
            // Arrange
            $brand_store1 = new BrandStore(2, 1);
            $brand_store2 = new BrandStore(3, 17);
            $brand_store3 = new BrandStore(2, 7);
            $brand_store4 = new BrandStore(13, 17);
            $brand_store1->save();
            $brand_store2->save();
            $brand_store3->save();
            $brand_store4->save();

            // Act
            BrandStore::deleteSome('brand_and_store_ids', 3, 17);
            BrandStore::deleteSome('brand_and_store_ids', 13, 17);

            // Assert
            $this->assertEquals(
                [$brand_store1, $brand_store3],
                BrandStore::getAll()
            );
        }

        function test_BrandStore_find()
        {
            // Arrange
            $brand_store1 = new BrandStore(2, 1);
            $brand_store2 = new BrandStore(3, 17);
            $brand_store3 = new BrandStore(2, 7);
            $brand_store4 = new BrandStore(13, 17);
            $brand_store1->save();
            $brand_store2->save();
            $brand_store3->save();
            $brand_store4->save();

            // Act
            $found_brand_store = BrandStore::find($brand_store3->getId());

            // Assert
            $this->assertEquals(
                $brand_store3,
                $found_brand_store
            );
        }

        function test_BrandStore_getSome_brand_id()
        {
            // Arrange
            $brand_store1 = new BrandStore(2, 1);
            $brand_store2 = new BrandStore(3, 17);
            $brand_store3 = new BrandStore(2, 7);
            $brand_store4 = new BrandStore(13, 17);
            $brand_store1->save();
            $brand_store2->save();
            $brand_store3->save();
            $brand_store4->save();

            // Act
            $found_brand_stores = BrandStore::getSome('brand_id', 2);

            // Assert
            $this->assertEquals(
                [$brand_store1, $brand_store3],
                $found_brand_stores
            );
        }

        function test_BrandStore_getSome_store_id()
        {
            // Arrange
            $brand_store1 = new BrandStore(2, 1);
            $brand_store2 = new BrandStore(3, 17);
            $brand_store3 = new BrandStore(2, 7);
            $brand_store4 = new BrandStore(13, 17);
            $brand_store1->save();
            $brand_store2->save();
            $brand_store3->save();
            $brand_store4->save();

            // Act
            $found_brand_stores = BrandStore::getSome('store_id', 17);

            // Assert
            $this->assertEquals(
                [$brand_store2, $brand_store4],
                $found_brand_stores
            );
        }
    }
?>

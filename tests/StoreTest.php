<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Store.php";
    require_once "src/BrandStore.php";

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);


    class StoreTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Store::deleteAll();
        }

        function test_Store_get_set_construct()
        {
            // Arrange
            $store1 = new Store('Skeechers');

            // Act
            $store2 = new Store('Vasque');
            $store2->setName($store1->getName());

            // Assert
            $this->assertEquals(
                ['Skeechers'],
                [$store2->getName()]
            );
        }

        function test_Store_save_deleteAll_getAll()
        {
            // Arrange
            $store1 = new Store('Skeechers');
            $store2 = new Store('Vasque');

            // Act
            $store1->save();
            $store2->save();

            Store::deleteAll();

            $store3 = new Store("BoBo's");
            $store4 = new Store('Nike');
            $store3->save();
            $store4->save();

            // Assert
            $this->assertEquals(
                [$store3, $store4],
                Store::getAll()
            );
        }

        function test_Store_update()
        {
            // Arrange
            $store1 = new Store('Skeechers');
            $store1->save();

            // Act
            $store1->update('Vasque');
            $stores = Store::getAll();
            $store2 = $stores[0];

            // Assert
            $this->assertEquals(
                ['Vasque'],
                [$store2->getName()]
            );

            // Act
            $store1->update("BoBo's");
            $stores = Store::getAll();
            $store2 = $stores[0];

            // Assert
            $this->assertEquals(
                ["BoBo's"],
                [$store2->getName()]
            );
        }

        function test_Store_delete()
        {
            // Arrange
            $store1 = new Store('Skeechers');
            $store2 = new Store('BoBos');
            $store3 = new Store('Vasque');
            $store1->save();
            $store2->save();
            $store3->save();

            // Act
            $store2->delete();

            // Assert
            $this->assertEquals(
                [$store1, $store3],
                Store::getAll()
            );
        }

        function test_Store_find()
        {
            // Arrange
            $store1 = new Store('Skeechers');
            $store2 = new Store('Hush Puppies');
            $store3 = new Store('Anne Somebody');
            $store4 = new Store('No Name');
            $store1->save();
            $store2->save();
            $store3->save();
            $store4->save();

            // Act
            $found_store = Store::find($store3->getId());

            // Assert
            $this->assertEquals(
                $store3,
                $found_store
            );
        }

        function test_Store_getSome_store_id()
        {
            // Arrange
            $store1 = new Store('Skeechers');
            $store2 = new Store('BoBos');
            $store3 = new Store('Vasque');
            $store4 = new Store('Tony Look');
            $store1->save();
            $store2->save();
            $store3->save();
            $store4->save();

            $brand_store1 = new BrandStore(7, $store4->getId());
            $brand_store2 = new BrandStore(3, $store3->getId());
            $brand_store3 = new BrandStore(3, $store2->getId());
            $brand_store4 = new BrandStore(9, $store1->getId());
            $brand_store1->save();
            $brand_store2->save();
            $brand_store3->save();
            $brand_store4->save();

            // Act
            $found_stores = Store::getSome('brand_id', 3);

            // Assert
            $this->assertEquals(
                [$store2, $store3],
                $found_stores
            );
        }

        function test_Store_getSome_name()
        {
            // Arrange
            $store1 = new Store('Skeechers');
            $store2 = new Store("BoBo's");
            $store3 = new Store('Vasque');
            $store4 = new Store("BoBo's");
            $store1->save();
            $store2->save();
            $store3->save();
            $store4->save();

            // Act
            $found_stores = Store::getSome('name', "BoBo's");

            // Assert
            $this->assertEquals(
                [$store2, $store4],
                $found_stores
            );
        }
    }
?>

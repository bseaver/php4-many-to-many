<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Brand.php";
    require_once "src/BrandStore.php";

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);


    class BrandTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Brand::deleteAll();
        }

        function test_Brand_get_set_construct()
        {
            // Arrange
            $brand1 = new Brand('Skeechers');

            // Act
            $brand2 = new Brand('Vasque');
            $brand2->setName($brand1->getName());

            // Assert
            $this->assertEquals(
                ['Skeechers'],
                [$brand2->getName()]
            );
        }

        function test_Brand_save_deleteAll_getAll()
        {
            // Arrange
            $brand1 = new Brand('Skeechers');
            $brand2 = new Brand('Vasque');

            // Act
            $brand1->save();
            $brand2->save();

            Brand::deleteAll();

            $brand3 = new Brand("BoBo's");
            $brand4 = new Brand('Nike');
            $brand3->save();
            $brand4->save();

            // Assert
            $this->assertEquals(
                [$brand3, $brand4],
                Brand::getAll()
            );
        }

        function test_Brand_update()
        {
            // Arrange
            $brand1 = new Brand('Skeechers');
            $brand1->save();

            // Act
            $brand1->update('Vasque');
            $brands = Brand::getAll();
            $brand2 = $brands[0];

            // Assert
            $this->assertEquals(
                ['Vasque'],
                [$brand2->getName()]
            );

            // Assert
            $this->assertEquals(
                [$brand1->getName()],
                [$brand2->getName()]
            );

            // Act
            $brand1->update("BoBo's");
            $brands = Brand::getAll();
            $brand2 = $brands[0];

            // Assert
            $this->assertEquals(
                ["BoBo's"],
                [$brand2->getName()]
            );
        }

        function test_Brand_delete()
        {
            // Arrange
            $brand1 = new Brand('Skeechers');
            $brand2 = new Brand('BoBos');
            $brand3 = new Brand('Vasque');
            $brand1->save();
            $brand2->save();
            $brand3->save();

            // Act
            $brand2->delete();

            // Assert
            $this->assertEquals(
                [$brand1, $brand3],
                Brand::getAll()
            );
        }

        function test_Brand_find()
        {
            // Arrange
            $brand1 = new Brand('Skeechers');
            $brand2 = new Brand('Hush Puppies');
            $brand3 = new Brand('Anne Somebody');
            $brand4 = new Brand('No Name');
            $brand1->save();
            $brand2->save();
            $brand3->save();
            $brand4->save();

            // Act
            $found_brand = Brand::find($brand3->getId());

            // Assert
            $this->assertEquals(
                $brand3,
                $found_brand
            );
        }

        function test_Brand_getSome_store_id()
        {
            // Arrange
            $brand1 = new Brand('Skeechers');
            $brand2 = new Brand('BoBos');
            $brand3 = new Brand('Vasque');
            $brand4 = new Brand('Tony Look');
            $brand1->save();
            $brand2->save();
            $brand3->save();
            $brand4->save();

            $brand_store1 = new BrandStore($brand4->getId(), 7);
            $brand_store2 = new BrandStore($brand3->getId(), 3);
            $brand_store3 = new BrandStore($brand2->getId(), 3);
            $brand_store4 = new BrandStore($brand1->getId(), 9);
            $brand_store1->save();
            $brand_store2->save();
            $brand_store3->save();
            $brand_store4->save();

            // Act
            $found_brands = Brand::getSome('store_id', 3);

            // Assert
            $this->assertEquals(
                [$brand2, $brand3],
                $found_brands
            );
        }

        function test_Brand_getSome_null_store_id()
        {
            // // Arrange
            // $brand1 = new Brand('Skeechers');
            // $brand2 = new Brand('BoBos');
            // $brand3 = new Brand('Vasque');
            // $brand4 = new Brand('Tony Look');
            // $brand1->save();
            // $brand2->save();
            // $brand3->save();
            // $brand4->save();
            //
            // $brand_store1 = new BrandStore($brand4->getId(), 7);
            // $brand_store2 = new BrandStore($brand3->getId(), 3);
            // $brand_store3 = new BrandStore($brand2->getId(), 3);
            // $brand_store4 = new BrandStore($brand1->getId(), 9);
            // $brand_store1->save();
            // $brand_store2->save();
            // $brand_store3->save();
            // $brand_store4->save();
            //
            // // Act
            // $found_brands = Brand::getSome('null_store_id', 3);
            //
            // // Assert
            // $this->assertEquals(
            //     [$brand1, $brand3],
            //     $found_brands
            // );
        }

        function test_Brand_getSome_name()
        {
            // Arrange
            $brand1 = new Brand('Skeechers');
            $brand2 = new Brand("BoBo's");
            $brand3 = new Brand('Vasque');
            $brand4 = new Brand("BoBo's");
            $brand1->save();
            $brand2->save();
            $brand3->save();
            $brand4->save();

            // Act
            $found_brands = Brand::getSome('name', "BoBo's");

            // Assert
            $this->assertEquals(
                [$brand2, $brand4],
                $found_brands
            );
        }
    }
?>

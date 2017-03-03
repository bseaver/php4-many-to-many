<?php
    class BrandStore
    {
        private $id;
        private $brand_id;
        private $store_id;


        function __construct($brand_id = null, $store_id = null, $id = null)
        {
            $this->setBrandId($brand_id);
            $this->setStoreId($store_id);
            $this->setId($id);
        }

        function getId()
        {
            return $this->id;
        }

        function getBrandId()
        {
            return $this->brand_id;
        }

        function getStoreId()
        {
            return $this->store_id;
        }

        function setId($id)
        {
            $this->id = (int) $id;
        }

        function setBrandId($brand_id)
        {
            $this->brand_id = (int) $brand_id;
        }

        function setStoreId($store_id)
        {
            $this->store_id = (int) $store_id;
        }

        function save()
        {
            $GLOBALS['DB']->exec(
            "INSERT INTO brands_stores
                (brand_id, store_id) VALUES
                ({$this->getBrandId()}, {$this->getStoreId()});"
            );
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($brand_id, $store_id)
        {
            $this->setBrandId($brand_id);
            $this->setStoreId($store_id);

            $GLOBALS['DB']->exec(
                "UPDATE brands_stores SET
                    brand_id = {$this->getBrandId()},
                    store_id = {$this->getStoreId()}
                WHERE id = {$this->getId()};"
            );
        }

        static function getSome($search_selector, $search_argument = '')
        {
            $output = array();
            $query = "";

            if ($search_selector == 'id') {
                $query = "SELECT * FROM brands_stores WHERE id = $search_argument;";
            }
            if ($search_selector == 'all') {
                $query = "SELECT * FROM brands_stores;";
            }
            if ($search_selector == 'brand_id') {
                $query = "SELECT * FROM brands_stores WHERE brand_id = $search_argument;";
            }
            if ($search_selector == 'store_id') {
                $query = "SELECT * FROM brands_stores WHERE store_id = $search_argument;";
            }

            if ($query) {
                $results = $GLOBALS['DB']->query($query);
                foreach ($results as $result) {
                        $author_book = new BrandStore(
                        $result['brand_id'],
                        $result['store_id'],
                        $result['id']
                    );
                    array_push($output, $author_book);
                }
            }
            return $output;
        }

        static function getAll()
        {
            return self::getSome('all');
        }

        static function deleteSome($search_selector, $search_argument = 0)
        {
            $delete_command = '';

            if ($search_selector == 'id') {
                $delete_command = "DELETE FROM brands_stores WHERE id = $search_argument;";
            }
            if ($search_selector == 'all') {
                $delete_command = "DELETE FROM brands_stores;";
            }
            if ($search_selector == 'brand_id') {
                $delete_command = "DELETE FROM brands_stores WHERE brand_id = $search_argument;";
            }
            if ($search_selector == 'store_id') {
                $delete_command = "DELETE FROM brands_stores WHERE store_id = $search_argument;";
            }

            if ($delete_command) {
                $GLOBALS['DB']->exec($delete_command);
            }
        }

        static function deleteAll()
        {
            self::deleteSome('all');
        }

        static function find($id)
        {
            $result = self::getSome('id', $id);
            return $result[0];
        }

        function delete()
        {
            $this->deleteSome('id', $this->getId());
        }
    }
?>

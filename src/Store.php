<?php
    class Store
    {
        private $id;
        private $name;


        function __construct($name = '', $id = null)
        {
            $this->setName($name);
            $this->setId($id);
        }

        function getId()
        {
            return $this->id;
        }

        function getName()
        {
            return $this->name;
        }

        function setId($id)
        {
            $this->id = (int) $id;
        }

        function setName($name)
        {
            $this->name = (string) $name;
        }

        function save()
        {
            $GLOBALS['DB']->exec(
            "INSERT INTO stores
                (name) VALUES
                ('{$this->getName()}');"
            );
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($name)
        {
            $this->setName($name);

            $GLOBALS['DB']->exec(
                "UPDATE stores SET
                    name = '{$this->getName()}'
                WHERE id = {$this->getId()};"
            );
        }

        static function getSome($search_selector, $search_argument = '')
        {
            $output = array();
            $query = "";

            if ($search_selector == 'id') {
                $query = "SELECT * FROM stores WHERE id = $search_argument ORDER BY name;";
            }
            if ($search_selector == 'all') {
                $query = "SELECT * FROM stores ORDER BY name;";
            }
            if ($search_selector == 'brand_id') {
                $query =
                    "SELECT stores.*
                    FROM brands_stores
                    JOIN stores ON brands_stores.store_id = stores.id
                    WHERE brands_stores.brand_id = $search_argument
                    ORDER BY name;";
            }

            if ($query) {
                $results = $GLOBALS['DB']->query($query);
                foreach ($results as $result) {
                        $new_store = new Store(
                        $result['name'],
                        $result['id']
                    );
                    array_push($output, $new_store);
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
                $delete_command = "DELETE FROM stores WHERE id = $search_argument;";
            }
            if ($search_selector == 'all') {
                $delete_command = "DELETE FROM stores;";
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

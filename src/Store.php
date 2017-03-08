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
            $statement_handle = $GLOBALS['DB']->prepare(
                "INSERT INTO stores (name) VALUES (:name);"
            );
            $statement_handle->bindValue(':name', $this->getName(), PDO::PARAM_STR);
            $statement_handle->execute();
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($name)
        {
            $this->setName($name);
            $statement_handle = $GLOBALS['DB']->prepare(
                "UPDATE stores SET
                    name = :name
                WHERE id = :id ;"
            );
            $statement_handle->bindValue(':name', $this->getName(), PDO::PARAM_STR);
            $statement_handle->bindValue(':id', $this->getId(), PDO::PARAM_INT);
            $statement_handle->execute();
        }

        static function getSome($search_selector, $search_argument = '')
        {
            $output = array();
            $statement_handle = null;

            if ($search_selector == 'id') {
                $statement_handle = $GLOBALS['DB']->prepare(
                    "SELECT * FROM stores WHERE id = :search_argument ORDER BY name, id;"
                );
                $statement_handle->bindValue(':search_argument', $search_argument, PDO::PARAM_STR);
            }

            if ($search_selector == 'name') {
                $statement_handle = $GLOBALS['DB']->prepare(
                    "SELECT * FROM stores WHERE `name` = :search_argument ORDER BY name, id;"
                );
                $statement_handle->bindValue(':search_argument', $search_argument, PDO::PARAM_STR);
            }

            if ($search_selector == 'all') {
                $statement_handle = $GLOBALS['DB']->prepare("SELECT * FROM stores ORDER BY name, id;");
            }

            if ($search_selector == 'brand_id') {
                $statement_handle = $GLOBALS['DB']->prepare(
                    "SELECT stores.*
                    FROM brands_stores
                    JOIN stores ON brands_stores.store_id = stores.id
                    WHERE brands_stores.brand_id = :search_argument
                    ORDER BY name, id;"
                );
                $statement_handle->bindValue(':search_argument', $search_argument, PDO::PARAM_INT);
            }

            if ($search_selector == 'null_brand_id') {
                $statement_handle = $GLOBALS['DB']->prepare(
                    "SELECT stores.*
                    FROM stores
                    WHERE NOT EXISTS
                        (SELECT *
                        FROM brands_stores
                        WHERE
                            brands_stores.brand_id = :search_argument AND
                            brands_stores.store_id = stores.id
                        )
                    ORDER BY stores.name, stores.id;"
                );
                $statement_handle->bindValue(':search_argument', $search_argument, PDO::PARAM_INT);
            }

            if ($statement_handle) {
                $statement_handle->execute();
                $results = $statement_handle->fetchAll();
                // $results = $GLOBALS['DB']->query($query);
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

        static function deleteSome($search_selector, $search_argument = 0)
        {
            $statement_handle = null;

            if ($search_selector == 'id') {
                $statement_handle = $GLOBALS['DB']->prepare(
                    "DELETE FROM stores WHERE id = :search_argument;"
                );
                $statement_handle->bindValue(':search_argument', $search_argument, PDO::PARAM_INT);
            }
            if ($search_selector == 'all') {
                $statement_handle = $GLOBALS['DB']->prepare("DELETE FROM stores;");
            }

            if ($statement_handle) {
                $statement_handle->execute();
            }
        }

        static function getAll()
        {
            return self::getSome('all');
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

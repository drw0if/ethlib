<?php
    require_once "Utils.php";

    /* Evito che la pagina possa essere richiesta direttamente */
    //exitIfRequested(__FILE__);

    require_once "DB.php";

    abstract class Entity{
        private $db = null;

        /*
            Takes an array of class public properties and builds up
            - *attributeList* with the props name
            - *valuesPlaceholder* for the PDO object
            - *params* list for the PDO execution
        */

        private function buildInsertQuery($props){
            //Remove id entry
            unset($props[$this->getTableId()]);

            $attributeList = [];
            $valuesPlaceholder = [];
            $params = [];

            //Collect query parts
            foreach ($props as $k => $v) {
                $attributeList[] = $k;
                $valuesPlaceholder[] = '?';
                $params[] = $v;
            }

            //Build strings out of lists
            $attributeList = implode(', ', $attributeList);
            $valuesPlaceholder = implode(', ', $valuesPlaceholder);

            //Build proper query
            $query = "INSERT INTO {$this->getTableName()}({$attributeList}) VALUES ({$valuesPlaceholder});";

            //Return query and params list
            return array($query, $params);
        }

        private function buildUpdateQuery($props){
            $setList = [];
            $params = [];

            foreach($props as $k => $v){
                $setList[] = "{$k} = ?";
                $params[] = $v;
            }

            $setList = implode(', ', $setList);
            $tableId = $this->getTableId();

            //Build proper query
            $query = "UPDATE {$this->getTableName()} SET {$setList} WHERE {$tableId} = {$this->{$tableId}}";
            return array($query, $params);
        }

        public function save(){
            $props = getProperties($this);
            $tableId = $this->getTableId();

            $query = '';
            $params = [];

            if($this->$tableId === null){
                //Build insert query
                $tmp = $this->buildInsertQuery($props);
                $query = $tmp[0];
                $params = $tmp[1];
            }
            else{
                //build update query
                $tmp = $this->buildUpdateQuery($props);
                $query = $tmp[0];
                $params = $tmp[1];
            }

            try{
                $db = DB::getInstance();
                $db->exec($query, $params);
            }
            catch(Exception $e){
                ThrowDatabaseError();
            }
        }

        public function filter_by(){

        }

        private function getTableName(){
            return strtolower(get_class($this));
        }

        private function getTableId(){
            return $this->getTableName() . '_id';
        }

        /* Prevent cloning */
        private function __clone(){}
    }

    class Book extends Entity{

        public $book_id = null;
        public $hash = null;
        public $isbn = null;
        public $local_name = null;
        public $file_type = null;
        public $name = null;
        public $mark_sum = null;
        public $mark_count = null;
        public $user_id = null;

        public function __construct($isbn){
            $this->isbn = $isbn;
        }
    }


    $b = new Book('123asd');
    $b->book_id = 1;
    $b->save();


?>
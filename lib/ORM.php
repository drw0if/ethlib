<?php
    require_once "Utils.php";

    /* 404 if directly requested */
    exitIfRequested(__FILE__);

    require_once "DB.php";

    abstract class Entity{
        private function buildInsertQuery($props){
            //Remove id entry
            unset($props[static::getTableId()]);

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

            //Collect query parts
            foreach($props as $k => $v){
                $setList[] = "{$k} = ?";
                $params[] = $v;
            }

            //Build strings out of lists
            $setList = implode(', ', $setList);
            $tableId = static::getTableId();

            //Build proper query
            $query = "UPDATE {$this->getTableName()} SET {$setList} WHERE {$tableId} = {$this->$tableId};";

            //Return query and params list
            return array($query, $params);
        }

        public function save(){
            $props = static::getProperties();
            $tableId = static::getTableId();

            $query = '';
            $params = [];
            $tmp = null;

            if($this->$tableId === null){
                //Build insert query
                $tmp = static::buildInsertQuery($props);
            }
            else{
                //build update query
                $tmp = static::buildUpdateQuery($props);
            }

            $query = $tmp[0];
            $params = $tmp[1];

            try{
                $db = DB::getInstance();
                $db->exec($query, $params);
            }
            catch(Exception $e){
                ThrowDatabaseError();
            }
        }

        private function getProperties(){
            return get_object_vars($this);
        }

        public function delete(){
            $tableIdProp = $this->getTableId();

            $query = "DELETE FROM {$this->getTableName()} WHERE {$tableIdProp} = ?;";
            $params = [$this->{$tableIdProp}];

            try{
                $db = DB::getInstance();
                $db->exec($query, $params);
            }
            catch(Exception $e){
                ThrowDatabaseError();
            }
        }

        public static function filter_by($where = []){
            $tableName = static::getTableName();

            //Build general query
            $query = "SELECT * FROM {$tableName}";

            $whereClause = [];
            $params = [];

            //Collect query parts
            foreach(static::getPropertyList() as $k => $v){
                if(array_key_exists($k, $where)){
                    $whereClause[] = "{$k} = ?";
                    $params[] = $where[$k];
                }
            }

            //complete query
            if(count($whereClause) > 0){
                $whereClause = implode(' AND ', $whereClause);
                $query .= " WHERE {$whereClause};";
            }
            else $query .= ";";

            try{
                $db = DB::getInstance();
                return $db->exec($query, $params);
            }
            catch(Exception $e){
                throwDatabaseError();
            }
        }

        private static function getPropertyList(){
            return get_class_vars(static::class);
        }

        private static function getTableName(){
            return strtolower(static::class);
        }

        private static function getTableId(){
            return static::getTableName() . '_id';
        }

        /* Prevent cloning */
        private function __clone(){}
    }

?>
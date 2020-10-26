<?php

    require_once "Utils.php";

    /* Evito che la pagina possa essere richiesta direttamente */
    exitIfRequested(__FILE__);

    /* DB Singleton */
    /* Si usa un singleton per avere al massimo una sola connessione al DB per ogni richiesta */
    class DB{

        private static $instance = null;

        private $hostname = "localhost";
        private $dbname = "ethib";
        private $username = "root";
        private $password = "";

        public static function getInstance(){
            if(static::$instance === null){
                static::$instance = new DB();
            }

            return static::$instance;
        }

        private function __construct(){
            try{


            }
            catch(PDOException $e){
                die("Error connecting with the database");
            }
        }

        public function __destruct(){
            $this->connection = null;
            static::$instance = null;
        }

        public function exec($query, $values = []){
            print_r($query);
            var_dump($values);
            return null;
        }

        /* Per evitare che l'oggetto possa essere clonato */
        private function __clone(){
        }
    }
?>
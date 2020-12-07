<?php

    require_once __DIR__ . "/ORM.php";

    class User extends Entity{
        public $user_id;
        public $username;
        public $email;
        public $hash;
        public $user_type;

        public function __construct(){
            $this->user_type = new DefaultValue();
        }

        public function setPassword($password){
            $this->hash = password_hash($password, PASSWORD_DEFAULT);
        }

        public static function login($username, $password){
            $resultSet = parent::filter_by([
                'username' => $username
            ]);

            if(count($resultSet) == 0)
                return null;

            $record = $resultSet[0];

            if(password_verify($password, $record['hash'])){
                return parent::toObject($record);
            }

        }

    }

    class Book extends Entity{
        public $book_id;
        public $isbn;
        public $local_name;
        public $file_type;
        public $name;
        public $mark_sum;
        public $mark_count;
        public $private;
        public $user_id;

        public function __construct(){
            $this->isbn = new DefaultValue();
            $this->mark_sum = new DefaultValue();
            $this->mark_count = new DefaultValue();
            $this->private = new DefaultValue();
        }

        public static function lastTenPublic(){
            $ans = Book::filter_by(['limit' => 10, 'private' => false], 'book_id', false);

            foreach($ans as $k => &$v)
                $v = static::toObject($v);

            return $ans;
        }

        public function openData(){
            if($this->isbn == NULL) return NULL;
            $isbnEndpoint = "https://openlibrary.org/isbn/{$this->isbn}.json";

            $ch = curl_init($isbnEndpoint);

            //Follow redirect since api does that
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            //Follow maximum 1 redirect
            curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
            //Ignore self signed SSL certificate
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //Return response body insted of printing it
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            curl_close($ch);

            $decoded = json_decode($result);

            return $decoded;
        }

    }

    class Mark extends Entity{
        public $mark_id;
        public $value;
        public $user_id;
        public $book_id;
    }

    class Review extends Entity{
        public $review_id;
        public $title;
        public $content;
        public $user_id;
        public $book_id;
    }

    class Author extends Entity{
        public $author_id;
        public $name_surname;
    }

    class Genre extends Entity{
        public $genre_id;
        public $name;
    }

    /*
    class Author_Book extends Entity{
        public $author_id;
        public $book_id;
    }

    class Genre_Book extends Entity{
        public $genre_id;
        public $book_id;
    }
    */
?>
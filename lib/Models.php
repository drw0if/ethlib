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
        public $rating_sum;
        public $rating_count;
        public $private;
        public $user_id;

        public function __construct(){
            $this->isbn = new DefaultValue();
            $this->rating_sum = new DefaultValue();
            $this->rating_count = new DefaultValue();
            $this->private = new DefaultValue();
        }

        public static function lastTenPublic(){
            $ans = Book::filter_by(['limit' => 10, 'private' => false], 'book_id', false);

            foreach($ans as $k => &$v)
                $v = static::toObject($v);

            return $ans;
        }

        public static function search($query){
            $args = ["%" . $query . "%"];
            $query = "SELECT * FROM Book WHERE name LIKE ?";

            $db = DB::getInstance();
            $ans = $db->exec($query, $args);

            foreach($ans as $k => &$v)
                $v = static::toObject($v);

            return $ans;
        }

    }

    class Review extends Entity{
        public $review_id;
        public $title;
        public $content;
        public $rating;
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
<?php

    require_once __DIR__ . '/ORM.php';

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
            $ans = static::filter_by([
                'username' => $username
            ]);

            if(count($ans) == 0)
                return null;

            $record = $ans[0];

            if(password_verify($password, $record['hash'])){
                return static::toObject($record);
            }

            return null;
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

        public static function search($query, $offset = 0){
            $args = ['%' . $query . '%', $offset];
            $query = 'SELECT `book_id`, `isbn`, `name`  FROM book WHERE private = FALSE AND name LIKE ? ORDER BY book_id DESC LIMIT ?, 10';

            $db = DB::getInstance();
            $ans = $db->exec($query, $args);

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
?>
<?php

    require_once "ORM.php";

    class User extends Entity{
        public $user_id;
        public $username;
        public $email;
        public $hash;
        public $salt;
    }

    class Book extends Entity{
        public $book_id;
        public $hash;
        public $isbn;
        public $local_name;
        public $file_type;
        public $name;
        public $mark_sum;
        public $mark_count;
        public $user_id;
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
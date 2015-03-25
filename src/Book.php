<?php
    class Book
    {
        private $book_id;
        private $title;

        function __construct($title, $book_id=null)
        {
            $this->book_id = $book_id;
            $this->title = $title;
        }

        function setBookId($new_book_id)
        {
            $this->book_id = $new_book_id;
        }

        function getBookId()
        {
            return $this->book_id;
        }

        function getTitle()
        {
            return $this->title;
        }

        function setTitle($new_title)
        {
            $this->title = $new_title;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO books (title) VALUES ('{$this->getTitle()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setBookId($result['id']);
        }

        function addAuthor($author)
        {
            $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$this->getBookId()},{$author->getAuthorId()});");
        }

        function getAuthors()
        {
            $returned_results = $GLOBALS['DB']->query("SELECT authors.* FROM books JOIN books_authors ON (books.id = books_authors.book_id) JOIN authors ON (books_authors.author_id = authors.id) WHERE books.id = {$this->getBookId()};");
            $authors = [];
            foreach($returned_results as $result) {
                $name = $result['name'];
                $id = $result['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function getAll()
        {
        $returned_results = $GLOBALS['DB']->query("SELECT * FROM books;");
        $books = [];

        foreach($returned_results as $result) {
            $title = $result['title'];
            $book_id= $result['id'];
            $new_book = new Book($title, $book_id);
            array_push($books, $new_book);
            }
        return $books;
        }

        static function find($id)
        {
            $statement = $GLOBALS['DB']->query("SELECT * FROM books WHERE id = {$id};");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $found_book = new Book($result['title'], $result['id']);
            return $found_book;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books *;");
        }

        function update($new_title)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getBookId()};");
            $this->setTitle($new_title);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getBookId()};");
            $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE book_id = {$this->getBookId()};");
        }




    }
?>

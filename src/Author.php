<?php
    class Author
    {
        private $name;
        private $author_id;

        function __construct($name, $author_id=null)
        {
            $this->author_id = $author_id;
            $this->name = $name;
        }

        function setAuthorId($new_author_id)
        {
            $this->author_id = $new_author_id;
        }

        function getAuthorId()
        {
            return $this->author_id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO authors (name) VALUES ('{$this->getName()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setAuthorId($result['id']);
        }

        function addBook($book)
        {
            $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$book->getBookId()},{$this->getAuthorId()});");
        }

        function getBooks()
        {
            $returned_results = $GLOBALS['DB']->query("SELECT books.* FROM authors JOIN books_authors ON (authors.id = books_authors.author_id) JOIN books ON (books_authors.book_id = books.id) WHERE authors.id = {$this->getAuthorId()};");
            $books = [];
            foreach($returned_results as $result) {
                $title = $result['title'];
                $id = $result['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function getAll()
        {
        $returned_results = $GLOBALS['DB']->query("SELECT * FROM authors;");
        $authors = [];

        foreach($returned_results as $result) {
            $name = $result['name'];
            $id= $result['id'];
            $new_author = new Author($name, $id);
            array_push($authors, $new_author);
            }
        return $authors;
        }

        static function find($id)
        {
            $statement = $GLOBALS['DB']->query("SELECT * FROM authors WHERE id = {$id};");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $found_author = new Author($result['name'], $result['id']);
            return $found_author;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors *;");
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM author WHERE id = {$this->getAuthorId()};");
            $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE author_id = {$this->getAuthorId()};");
        }


    }
?>

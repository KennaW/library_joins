<?php
/**
   * @backupGlobals disabled
   * @backupStaticAttributes disabled
   */

   require_once "src/Author.php";
   require_once "src/Book.php";

   $DB = new PDO('pgsql:host=localhost;dbname=test_catalog');

   class BookTestTest extends PHPUnit_Framework_TestCase {

       protected function tearDown() {
           Book::deleteAll();
           Author::deleteAll();
       }

       function test_setTitle() {
           //Arrange
           $title = "History of the World Part 1";
           $test_book = new Book($title);

           //Act
           $test_book->setTitle("World History");
           $result = $test_book->getTitle();

           //Assert
           $this->assertEquals("World History", $result);
       }

       function test_getTitle() {
           //Arrange
           $title = "History of the World Part 1";
           $test_book = new Book($title);

           //Act
           $result = $test_book->getTitle();

           //Assert
           $this->assertEquals($title, $result);
       }

       function test_setId() {
           //Arrange
           $title = "History of the World Part 1";
           $test_book = new Book($title);

           //Act
           $test_book->setBookId(3);
           $result = $test_book->getBookId();

           //Assert
           $this->assertEquals(3, $result);
       }

       function test_getId() {
           //Arrange
           $title = "History of the World Part 1";
           $test_book = new Book($title, 4);

           //Act
           $result = $test_book->getBookId();

           //Assert
           $this->assertEquals(4, $result);
       }

       function test_save()
       {
           //Arrange
           $title = "History of the World Part 1";
           $test_book = new Book($title);
           $test_book->save();

           //Act
           $result = Book::getAll();

           //Assert
           $this->assertEquals([$test_book], $result);

       }

       function test_deleteAll(){
           //Arrange
           $title = "History of the World Part 1";
           $test_book = new Book($title);
           $test_book->save();

           //Act
           Book::deleteAll();
           $result = Book::getAll();

           //Assert
           $this->assertEquals([], $result);

       }

       function test_addAuthor(){
           //Arrange
           $title = "History of the World Part 1";
           $test_book = new Book($title);
           $test_book->save();

           $name = "kenna";
           $test_author = new Author($name);
           $test_author->save();

           $name2 = "chitra";
           $test_author2 = new Author($name2);
           $test_author2->save();

           //Act
           $test_book->addAuthor($test_author);
           $test_book->addAuthor($test_author2);
           $result = $test_book->getAuthors();

           //Assert
           $this->assertEquals([$test_author,$test_author2], $result);

       }

























}

?>

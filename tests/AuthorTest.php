<?php
/**
   * @backupGlobals disabled
   * @backupStaticAttributes disabled
   */

   require_once "src/Author.php";
   require_once "src/Author.php";

   $DB = new PDO('pgsql:host=localhost;dbname=test_catalog');

   class AuthorTest extends PHPUnit_Framework_TestCase {

       protected function tearDown() {
           Book::deleteAll();
           Author::deleteAll();
       }

       function test_setName() {
           //Arrange
           $name = "Alexandre Dumas";
           $test_author = new Author($name);

           //Act
           $test_author->setName("Chitra");
           $result = $test_author->getName();

           //Assert
           $this->assertEquals("Chitra", $result);

       }

       function test_getName() {
           //Arrange
           $name = "Sharky McShark";
           $test_author = new Author($name);

           //Act
           $result = $test_author->getName();

           //Assert
           $this->assertEquals($name, $result);
       }

       function test_setAuthorId() {
           //Arrange
           $name = "Bob";
           $test_author = new Author($name);

           //Act
           $test_author->setAuthorId(3);
           $result = $test_author->getAuthorId();

           //Assert
           $this->assertEquals(3, $result);
       }

       function test_getId() {
           //Arrange
           $name = "John Stewart";
           $test_author = new Author($name, 4);

           //Act
           $result = $test_author->getAuthorId();

           //Assert
           $this->assertEquals(4, $result);
       }

       function test_save()
       {
           //Arrange
           $name = "Umesh";
           $test_author = new Author($name);
           $test_author->save();

           //Act
           $result = Author::getAll();

           //Assert
           $this->assertEquals([$test_author], $result);

       }

       function test_deleteAll(){
           //Arrange
           $name = "Umesha";
           $test_author = new Author($name);
           $test_author->save();

           //Act
           Author::deleteAll();
           $result = Author::getAll();

           //Assert
           $this->assertEquals([], $result);

       }

       function test_addBook(){
           //Arrange
           $name = "kenna";
           $test_author = new Author($name);
           $test_author->save();

           $title = "History of the World Part 1";
           $test_book = new Book($title);
           $test_book->save();

           $title2 = "The Count of Monte Cristo";
           $test_book2 = new Book($title2);
           $test_book2->save();

           //act
           $test_author->addBook($test_book);
           $test_author->addBook($test_book2);
           $result = $test_author->getBooks();

           //Assert
           $this->assertEquals([$test_book, $test_book2], $result);
       }


   }
?>

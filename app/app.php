<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Author.php";

    $app = new Silex\Application();
    $DB = new PDO('pgsql:host=localhost;dbname=catalog;');

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
      ));

      use Symfony\Component\HttpFoundation\Request;
      Request::enableHttpMethodParameterOverride();

      $app->get('/', function() use ($app) {

         return $app['twig']->render('home.html.twig',array('books'=>Book::getAll()));
      });

      $app->post('/add_books', function() use ($app) {
         $new_book = new Book($_POST['book_title']);
         $new_book->save();
         $books = Book::getAll();
         return $app['twig']->render('home.html.twig', array('books'=>Book::getAll()));
      });

      $app->get("/books/{id}", function($id) use ($app){
        $book = Book::find($id) ;
        return $app['twig']->render('book.html.twig', array('book'=>$book,'authors'=>$book->getAuthors()));
    });

    $app->post('/add_authors', function() use ($app) {
        $new_author = new Author($_POST['author_name']);
        $new_author->save();
        $book = Book::find($_POST['book_id']);
        $book->addAuthor($new_author);

        return $app['twig']->render('book.html.twig', array('book'=>$book, 'authors'=>$book->getAuthors()));
    });

    $app->get("/books/{id}/edit", function($id) use ($app) {
    $book = Book::find($id);
    return $app['twig']->render('book_edit.html.twig', array('book' => $book));
});


$app->patch("/books/{id}", function($id) use ($app) {
    $title = $_POST['title'];
    $book = Book::find($id);
    $book->update($title);
    return $app['twig']->render('book.html.twig', array('book' => $book,'authors' => $book->getAuthors()));
});

$app->delete("/books/{id}", function($id) use ($app) {
    $book = Book::find($id);
    $book->delete();
        return $app['twig']->render('home.html.twig', array('books'=>Book::getAll()));
    });




























      return $app;

?>

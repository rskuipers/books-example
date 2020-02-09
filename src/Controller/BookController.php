<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BookController extends AbstractController
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route(path="/", name="books_list")
     */
    public function listAction(): Response
    {
        $books = $this->bookRepository->getAllBooks();

        return $this->render(
            'books/list.html.twig',
            [
                'books' => $books,
            ]
        );
    }

    /**
     * @Route(path="/add", name="books_add")
     */
    public function addAction(Request $request): Response
    {
        $form = $this->createForm(BookType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $book = new Book();
            $book->setId(Uuid::uuid4());
            $book->setTitle($data['title']);
            $book->setAuthor($data['author']);
            $book->setReleaseDate($data['releaseDate']);

            $this->bookRepository->save($book);

            return $this->redirectToRoute('books_list');
        }

        return $this->render('books/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

<?php


namespace App\Controller;

use App\DTO\BookCreate;
use App\DTO\BookDTO;
use App\DTO\BookShow;
use App\DTO\BookUpdate;
use App\Entity\Book;
use App\Service\BookService;
use InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * @param Request $request
     * @Route("/book", name="book_add", methods={"POST"})
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        if (!BookCreate::isJson($request->getContent())) {
            throw new InvalidArgumentException('Wrong json format');
        }
        $bookDTO = BookCreate::createFromJson($request->getContent());
        $book = Book::createFrom($bookDTO);
        $this->bookService->save($book);

        return new JsonResponse(['status' => 'Book created!'], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @Route("/book/{book_isbn}", name="book_update", methods={"PUT"})
     * @ParamConverter("book", class="App\Entity\Book", options={"mapping": {"book_isbn" : "isbn"}})
     * @return JsonResponse
     */
    public function update(Book $book, Request $request): JsonResponse
    {
        if (!BookUpdate::isJson($request->getContent())) {
            throw new InvalidArgumentException('Wrong json format');
        }
        $bookDTO = BookUpdate::createFromJson($request->getContent());
        $book->updateFrom($bookDTO);
        $this->bookService->save($book);

        return new JsonResponse(['status' => 'Book updated!'], Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/book/{book_isbn}", name="book_show", methods={"GET"})
     * @ParamConverter("book", class="App\Entity\Book", options={"mapping": {"book_isbn" : "isbn"}})
     * @return JsonResponse
     */
    public function show(Book $book): JsonResponse
    {
        return new JsonResponse(BookShow::createFrom($book), Response::HTTP_ACCEPTED);
    }
}

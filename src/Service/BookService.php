<?php


namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;

class BookService
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function save(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    public function delete(Book $book): void
    {
        $this->bookRepository->delete($book);
    }
}

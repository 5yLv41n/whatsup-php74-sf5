<?php

namespace App\DataFixtures;

use App\DTO\BookCreate;
use App\Entity\Book;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    private const NB_BOOKS = 10;

    public function load(ObjectManager $objectManager): void
    {
        foreach ($this->generateBook() as $bookGenerated) {
            $json = json_encode($bookGenerated);
            $bookDTO = BookCreate::createFromJson($json);
            $book = Book::createFrom($bookDTO);
            $objectManager->persist($book);
        }
        $objectManager->flush();
    }

    private function generateBook(): iterable
    {
        for ($i=0; $i<self::NB_BOOKS; $i++) {
            yield [
                'isbn' => sprintf('ISBN-13:978-013708107%d', $i),
                'title' => sprintf('Title %d', $i),
                'description' => sprintf('Desc %d', $i),
                'publishingDate' => (new DateTimeImmutable())->format('e'),
            ];
        }
    }
}

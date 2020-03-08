<?php

namespace App\DataFixtures;

use App\DTO\BookCreate;
use App\Entity\Book;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    private const NB_BOOKS = 10;

    public static function getGroups(): array
    {
        return ['books'];
    }

    public function load(ObjectManager $objectManager): void
    {
        $user = $this->getReference(UserFixtures::USER_REFERENCE);
        foreach ($this->generateBook() as $bookGenerated) {
            $json = json_encode($bookGenerated);
            $bookDTO = BookCreate::createFromJson($json);
            $book = Book::createFrom($bookDTO);
            $book->setCreatedBy($user);
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

    public function getOrder()
    {
        return 20;
    }
}

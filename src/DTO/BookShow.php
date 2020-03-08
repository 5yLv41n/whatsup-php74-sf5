<?php

namespace App\DTO;

use App\Entity\Book;
use App\ValueObject\Isbn;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Class BookCreate
 * @package App\DTO
 * @todo
 * set validator
 */
class BookShow extends BookDTOAbstract implements JsonSerializable
{
    public Isbn $isbn;
    public string $title;
    public string $description;
    public DateTimeImmutable $publishingDate;

    private function __construct(Isbn $isbn, string $title, string $description, DateTimeImmutable $publishingDate)
    {
        if (empty($title) || empty($description) || null === $publishingDate) {
            throw new InvalidArgumentException('Expecting mandatory parameters');
        }

        $this->isbn = $isbn;
        $this->title = $title;
        $this->description = $description;
        $this->publishingDate = $publishingDate;
    }

    public static function createFromJson(string $json): BookDTO
    {
        $data = json_decode($json);
        $isbn = new Isbn($data->isbn);

        try {
            $publishingDate = new DateTimeImmutable($data->publishingDate);
        } catch (Exception $e) {
            throw new InvalidArgumentException('Invalid publishing date');
        }

        return new self($isbn, $data->title, $data->description, $publishingDate);
    }

    public static function createFrom(Book $book): self
    {
        return new self(new Isbn($book->getIsbn()), $book->getTitle(), $book->getDescription(), $book->getPublishingDate());
    }

    public function jsonSerialize()
    {
        return [
            'isbn' => $this->isbn->value,
            'title' => $this->title,
            'description' => $this->description,
            'publishingDate' => $this->publishingDate->format('c'),
        ];
    }
}

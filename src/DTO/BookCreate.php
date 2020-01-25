<?php


namespace App\DTO;

use App\ValueObject\Isbn;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;

/**
 * Class BookCreate
 * @package App\DTO
 */
class BookCreate extends BookDTOAbstract
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
}

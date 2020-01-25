<?php


namespace App\DTO;

use App\ValueObject\Isbn;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Class BookUpdate
 * @package App\DTO
 * @todo
 * set validator
 */
class BookUpdate extends BookDTOAbstract implements JsonSerializable
{
    public string $id;
    public Isbn $isbn;
    public string $title;
    public string $description;
    public DateTimeImmutable $publishingDate;

    private function __construct(string $title, string $description, DateTimeImmutable $publishingDate)
    {
        if (empty($title) || empty($description) || null === $publishingDate) {
            throw new InvalidArgumentException('Expecting mandatory parameters');
        }

        $this->title = $title;
        $this->description = $description;
        $this->publishingDate = $publishingDate;
    }

    public static function createFromJson(string $json): BookDTO
    {
        $data = json_decode($json);

        try {
            $publishingDate = new DateTimeImmutable($data->publishingDate);
        } catch (Exception $e) {
            throw new InvalidArgumentException('Invalid publishing date');
        }

        return new self($data->title, $data->description, $publishingDate);
    }

    public function jsonSerialize()
    {
        return [
            'isbn' => $this->isbn,
            'title'=> $this->title,
            'description'=> $this->description,
            'publishingDate'=> $this->publishingDate->format('c')
        ];
    }
}

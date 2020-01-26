<?php

namespace App\Entity;

use App\DTO\BookDTO;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\SoftDeletableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\SoftDeletable\SoftDeletableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Polyfill\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @UniqueEntity(fields={"isbn"}, message="ISBN already exists")
 */
class Book implements BlameableInterface, TimestampableInterface, SoftDeletableInterface, JsonSerializable
{
    use BlameableTrait, TimestampableTrait, SoftDeletableTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private string $isbn;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $publishingDate;

    private function __construct(string $isbn, string $title, string $description, DateTimeImmutable $publishingDate)
    {
        $this->setId();
        $this->isbn = $isbn;
        $this->title = $title;
        $this->description = $description;
        $this->publishingDate = $publishingDate;
    }

    public static function createFrom(BookDTO $bookDTO): Book
    {
        return new self($bookDTO->isbn->value, $bookDTO->title, $bookDTO->description, $bookDTO->publishingDate);
    }

    private function setId(): void
    {
        $this->id = Uuid::uuid_create(UUID_TYPE_RANDOM);
    }

    public function updateFrom(BookDTO $bookDTO): Book
    {
        $this->title = $bookDTO->title;
        $this->description = $bookDTO->description;
        $this->publishingDate = $bookDTO->publishingDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getPublishingDate(): DateTimeImmutable
    {
        return $this->publishingDate;
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

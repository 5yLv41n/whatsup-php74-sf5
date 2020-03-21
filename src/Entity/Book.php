<?php

namespace App\Entity;

use App\DTO\BookDTO;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use JsonSerializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Polyfill\Uuid\Uuid;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @UniqueEntity(fields={"isbn"}, message="ISBN already exists")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Book implements JsonSerializable
{
    use SoftDeleteableEntity;

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
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private DateTime $updatedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $publishingDate;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by")
     */
    private User $createdBy;

    private function __construct(
        string $isbn,
        string $title,
        string $description,
        DateTimeImmutable $publishingDate
    ) {
        $this->setId();
        $this->isbn = $isbn;
        $this->title = $title;
        $this->description = $description;
        $this->publishingDate = $publishingDate;
    }

    public static function createFrom(BookDTO $bookDTO): Book
    {
        return new self(
            $bookDTO->isbn->value,
            $bookDTO->title,
            $bookDTO->description,
            $bookDTO->publishingDate
        );
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

    public function getId(): string
    {
        return $this->id;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

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

    public function setCreatedBy(User $user): void
    {
        $this->createdBy = $user;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}

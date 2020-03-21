<?php


namespace App\Tests\Integration\DTO;

use App\DataFixtures\BookFixtures;
use App\DataFixtures\UserFixtures;
use App\DTO\BookDTO;
use App\DTO\BookShow;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Tests\Integration\FixtureAwareTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BookShowTest extends FixtureAwareTestCase
{
    private const BOOK_UUID_RANDOM = 'ISBN-13:978-013708107';
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->userPasswordEncoder = self::$container->get(UserPasswordEncoderInterface::class);
        $this->addFixture(new UserFixtures($this->userPasswordEncoder));
        $this->addFixture(new BookFixtures());
        $this->executeFixtures();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @test
     */
    public function it_creates_bookDTO_from_json(): void
    {
        $book = $this->getRandomBook();
        $bookDTO = BookShow::createFrom($book);
        $json = json_encode($bookDTO);
        $this->assertEquals($bookDTO, BookShow::createFromJson($json));
    }

    /**
     * @test
     */
    public function it_creates_bookDTO_from_object(): void
    {
        $book = $this->getRandomBook();
        $this->assertInstanceOf(BookDTO::class, BookShow::createFrom($book));
    }

    private function getRandomBook(): ?Book
    {
        /** @var BookRepository $bookRepository */
        $bookRepository = $this->entityManager->getRepository(Book::class);
        /** @var Book $book */
        $book = $bookRepository->findOneBy(['isbn' => self::BOOK_UUID_RANDOM.random_int(0,9)]);

        return $book;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $cmd = $this->entityManager->getClassMetadata(Book::class);
        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();

        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $connection->query(sprintf('DELETE FROM %s', $cmd->getTableName()));
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
        }
    }
}

<?php


namespace App\Tests\Unit\ValueObject;

use App\ValueObject\Isbn;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class IsbnTest extends TestCase
{
    private string $isbn10 = 'ISBN-10:0-8539-2136-9';
    private string $isbn13 = 'ISBN-13:978-0132350884';
    private ReflectionClass $reflector;

    protected function setUp()
    {
        parent::setUp();
        $this->reflector = new ReflectionClass(Isbn::class);
    }

    /**
     * @test
     */
    public function it_returns_invalid_isbn(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $isbn = new Isbn('test');
    }

    /**
     * @test
     * @dataProvider isbnProvider
     */
    public function it_returns_valid_isbn(string $isbn): void
    {
        $this->assertEquals($isbn, (new Isbn($isbn))->value);
    }

    public function isbnProvider(): iterable
    {
        yield [$this->isbn10];
        yield [$this->isbn13];
    }

    private function getConstValue(string $property)
    {
        return $this->reflector->getConstant($property);
    }

    private function getPrivateMethod(string $methodName)
    {
        $method = $this->reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}

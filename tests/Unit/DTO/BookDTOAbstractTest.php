<?php

namespace App\Tests\Unit\DTO;

use App\DTO\BookDTOAbstract;
use PHPUnit\Framework\TestCase;

class BookDTOAbstractTest extends TestCase
{
    private string $wrongJson = 'test';
    private string $goodJson = '{"test":"test"}';

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_is_invalid_json(): void
    {
        $this->assertFalse(BookDTOAbstract::isJson($this->wrongJson));
    }

    /**
     * @test
     */
    public function it_is_valid_json(): void
    {
        $this->assertTrue(BookDTOAbstract::isJson($this->goodJson));
    }
}

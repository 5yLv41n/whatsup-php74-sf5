<?php


namespace App\ValueObject;

use InvalidArgumentException;

class Isbn
{
    public string $value;
    private const ISBN10 = '/^(?:ISBN(?:-10)?:?\ *((?=\d{1,5}([ -]?)\d{1,7}\2?\d{1,6}\2?\d)(?:\d\2*){9}[\dX]))$/i';
    private const ISBN13 = '/^(?:ISBN(?:-13)?:?\ *(97(?:8|9)([ -]?)(?=\d{1,5}\2?\d{1,7}\2?\d{1,6}\2?\d)(?:\d\2*){9}\d))$/i';

    public function __construct(string $value)
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Wrong Isbn Format');
        }
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

    private function isValid(string $value): bool
    {
        return (1 === preg_match(self::ISBN10, $value, $matches)
            ||
            1 === preg_match(self::ISBN13, $value, $matches));
    }
}

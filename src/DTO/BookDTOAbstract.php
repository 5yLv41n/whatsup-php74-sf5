<?php


namespace App\DTO;

abstract class BookDTOAbstract implements BookDTO
{
    public static function isJson(string $json): bool
    {
        json_decode($json);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    abstract public static function createFromJson(string $json): BookDTO;
}

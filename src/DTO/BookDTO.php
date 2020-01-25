<?php


namespace App\DTO;

interface BookDTO
{
    public static function isJson(string $json): bool;
    public static function createFromJson(string $json): BookDTO;
}

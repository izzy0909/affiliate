<?php

namespace App\Services;

class AffiliateFileParserService
{
    public function parseFile(string $filePath): array
    {
        $fileLines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return array_values(array_filter(array_map(function ($line) {
            return json_validate($line) ? json_decode($line, true) : null;
        }, $fileLines)));
    }
}

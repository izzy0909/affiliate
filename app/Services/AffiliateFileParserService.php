<?php

namespace App\Services;

use Generator;
use SplFileObject;

class AffiliateFileParserService
{
    /**
     * @param string $filePath
     *
     * @return Generator
     */
    public function parseFile(string $filePath): Generator
    {
        $file = new SplFileObject($filePath, 'r');
        $file->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::SKIP_EMPTY);

        foreach ($file as $line) {
            if (!json_validate($line)) {
                continue;
            }

            $decoded = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                yield $decoded;
            }
        }
    }
}

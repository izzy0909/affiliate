<?php

namespace Tests\Unit\Rules;

use App\Rules\TxtFile;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TxtFileTest extends TestCase
{
    protected TxtFile $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new TxtFile();
    }

    public function testPassesWithValidTxtFile()
    {
        $file = UploadedFile::fake()->create('document.txt', 1, 'text/plain');

        $this->rule->validate('file', $file, function ($message) {
            $this->fail("Validation should have passed but failed with: $message");
        });

        $this->assertTrue(true, 'Validation passed as expected');
    }

    public function testFailsWithWrongExtension()
    {
        $file = UploadedFile::fake()->create('image.png', 1, 'text/plain');

        $failedMessage = null;

        $this->rule->validate('file', $file, function ($message) use (&$failedMessage) {
            $failedMessage = $message;
        });

        $this->assertEquals('The file must have a .txt extension.', $failedMessage);
    }

    public function testFailsWithInvalidMimeType()
    {
        $file = UploadedFile::fake()->create('document.txt', 1, 'application/pdf');

        $failedMessage = null;

        $this->rule->validate('file', $file, function ($message) use (&$failedMessage) {
            $failedMessage = $message;
        });

        $this->assertEquals('The file must be a valid text file.', $failedMessage);
    }
}

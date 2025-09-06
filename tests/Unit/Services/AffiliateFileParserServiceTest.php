<?php

namespace Tests\Unit\Services;

use App\Services\AffiliateFileParserService;
use Tests\TestCase;

class AffiliateFileParserServiceTest extends TestCase
{
    protected AffiliateFileParserService $service;
    protected string $testFilePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AffiliateFileParserService();

        // Create a temporary test file with valid and invalid JSON lines
        $lines = [
            '{"affiliate_id": 1, "name": "Alice"}',
            '{"affiliate_id": 2, "name": "Bob"}',
            'invalid json line',
            '{"affiliate_id": 3, "name": "Charlie"}',
        ];

        $this->testFilePath = tempnam(sys_get_temp_dir(), 'affiliates');
        file_put_contents($this->testFilePath, implode("\n", $lines));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    public function testParseFileFiltersInvalidJsonLines(): void
    {
        $resultGenerator = $this->service->parseFile($this->testFilePath);
        $result = iterator_to_array($resultGenerator);

        $this->assertCount(3, $result);

        foreach ($result as $affiliate) {
            $this->assertIsArray($affiliate);
            $this->assertArrayHasKey('affiliate_id', $affiliate);
            $this->assertArrayHasKey('name', $affiliate);
        }

        $this->assertEquals(1, $result[0]['affiliate_id']);
        $this->assertEquals('Alice', $result[0]['name']);
        $this->assertEquals(2, $result[1]['affiliate_id']);
        $this->assertEquals('Bob', $result[1]['name']);
        $this->assertEquals(3, $result[2]['affiliate_id']);
        $this->assertEquals('Charlie', $result[2]['name']);
    }

}

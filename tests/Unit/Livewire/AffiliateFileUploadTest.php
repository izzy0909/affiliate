<?php

namespace Tests\Unit\Livewire;

use App\Livewire\AffiliateFileUpload;
use App\Services\AffiliateDistanceService;
use App\Services\AffiliateFileParserService;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class AffiliateFileUploadTest extends TestCase
{
    protected AffiliateFileUpload $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AffiliateFileUpload();
    }

    public function testUpdatedAffiliateFileTriggersProcessing()
    {
        $mockParser = Mockery::mock(AffiliateFileParserService::class);
        $mockDistance = Mockery::mock(AffiliateDistanceService::class);

        $fakeFile = UploadedFile::fake()->create('affiliates.txt', 10);

        $parsedData = [['affiliate_id' => 1, 'name' => 'Test Affiliate', 'latitude'=>53.3, 'longitude'=>-6.2]];
        $filteredCollection = collect([['affiliate_id' => 1, 'name' => 'Test Affiliate']]);

        $mockParser->shouldReceive('parseFile')
            ->once()
            ->with(Mockery::type('string'))
            ->andReturn($parsedData);

        $mockDistance->shouldReceive('filterByDistance')
            ->once()
            ->with($parsedData)
            ->andReturn($filteredCollection);

        $this->app->instance(AffiliateFileParserService::class, $mockParser);
        $this->app->instance(AffiliateDistanceService::class, $mockDistance);

        Livewire::test(AffiliateFileUpload::class)
            ->set('affiliateFile', $fakeFile)
            ->assertSet('affiliateCollection', $filteredCollection)
            ->assertSet('errorMessage', null);
    }

    public function testProcessFileHandlesExceptionAndSetsErrorMessage()
    {
        $mockParser = Mockery::mock(AffiliateFileParserService::class);
        $mockDistance = Mockery::mock(AffiliateDistanceService::class);

        $fakeFile = UploadedFile::fake()->create('affiliates.txt', 10);

        // Simulate parseFile throwing an exception
        $mockParser->shouldReceive('parseFile')
            ->once()
            ->with(Mockery::type('string'))
            ->andThrow(new \Exception('Simulated parse error'));

        $this->app->instance(AffiliateFileParserService::class, $mockParser);
        $this->app->instance(AffiliateDistanceService::class, $mockDistance);

        Livewire::test(AffiliateFileUpload::class)
            ->set('affiliateFile', $fakeFile) // triggers updatedAffiliateFile()
            ->assertSet('affiliateCollection', null)
            ->assertSet('errorMessage', 'Error processing file: Simulated parse error');
    }


    public function testRulesAreCorrect()
    {
        $reflection = new \ReflectionClass(AffiliateFileUpload::class);
        $method = $reflection->getMethod('rules');
        $method->setAccessible(true);
        $rules = $method->invoke($this->service);;

        $this->assertArrayHasKey('affiliateFile', $rules);
        $this->assertContains('required', $rules['affiliateFile']);
        $this->assertContains('file', $rules['affiliateFile']);
        $this->assertContains('max:1024', $rules['affiliateFile']);
    }
}

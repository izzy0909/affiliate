<?php

namespace Tests\Unit\Services;

use App\Services\AffiliateDistanceService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AffiliateDistanceServiceTest extends TestCase
{
    protected AffiliateDistanceService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AffiliateDistanceService();
    }

    public function testFilterByDistanceFiltersAndSortsAffiliates(): void
    {
        // Prepare test affiliates around the office
        $affiliates = [
            ['affiliate_id' => 3, 'name' => 'Charlie', 'latitude' => 53.3340285, 'longitude' => -6.2535495], // Office location, dist 0
            ['affiliate_id' => 1, 'name' => 'Alice', 'latitude' => 54.0, 'longitude' => -6.0], // ~75 km away inside max distance
            ['affiliate_id' => 2, 'name' => 'Bob', 'latitude' => 56.0, 'longitude' => -7.0], // ~300 km away outside max distance
        ];

        $affiliatesGenerator = (function () use ($affiliates) {
            foreach ($affiliates as $affiliate) {
                yield $affiliate;
            }
        })();

        $result = $this->service->filterByDistance($affiliatesGenerator);

        // Assert only 2 affiliates within max distance
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);

        // Assert sorted by affiliate_id ascending
        $this->assertEquals(1, $result[0]['affiliate_id']);
        $this->assertEquals(3, $result[1]['affiliate_id']);

        // Assert only affiliate_id and name keys are present
        foreach ($result as $affiliate) {
            $this->assertArrayHasKey('affiliate_id', $affiliate);
            $this->assertArrayHasKey('name', $affiliate);
            $this->assertCount(2, $affiliate);
        }
    }


    public function testDistanceBetweenPointsReturnsZeroForSameCoordinates(): void
    {
        $reflection = new \ReflectionClass(AffiliateDistanceService::class);
        $method = $reflection->getMethod('distanceBetweenPoints');
        $method->setAccessible(true);

        $lat = 53.3340285;
        $lon = -6.2535495;

        $distance = $method->invokeArgs($this->service, [$lat, $lon]);

        $this->assertSame(0.0, $distance);
    }
}

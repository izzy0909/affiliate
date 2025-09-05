<?php

namespace App\Services;

use Generator;
use Illuminate\Support\Collection;

class AffiliateDistanceService
{
    private const int EARTH_RADIUS_KM = 6371;
    private const int MAX_DISTANCE = 100;

    private const float OFFICE_LATITUDE = 53.3340285;
    private const float OFFICE_LONGITUDE = -6.2535495;

    /**
     * @param Generator $affiliates
     *
     * @return Collection Filtered and sorted affiliates.
     */
    public function filterByDistance(Generator $affiliates): Collection
    {
        return collect($affiliates)
            ->filter(
                function ($affiliate) {
                    $distance = $this->distanceBetweenPoints(
                        $affiliate['latitude'],
                        $affiliate['longitude']
                    );

                    return $distance <= self::MAX_DISTANCE;
                }
            )
            ->sortBy('affiliate_id')
            ->values()
            ->map(
                fn($affiliate) => [
                    'affiliate_id' => $affiliate['affiliate_id'],
                    'name' => $affiliate['name'],
                ]
            );
    }

    /**
     * @param float $affiliateLatitude
     * @param float $affiliateLongitude
     *
     * @return float
     */
    private function distanceBetweenPoints(float $affiliateLatitude, float $affiliateLongitude): float
    {
        $officeLatitudeRadians = deg2rad(self::OFFICE_LATITUDE);
        $officeLongitudeRadians = deg2rad(self::OFFICE_LONGITUDE);
        $affiliateLatitudeRadians = deg2rad($affiliateLatitude);
        $affiliateLongitudeRadians = deg2rad($affiliateLongitude);

        $deltaLongitude = $affiliateLongitudeRadians - $officeLongitudeRadians;
        $deltaLatitude = $affiliateLatitudeRadians - $officeLatitudeRadians;

        $haversineFormulaPart = sin($deltaLatitude / 2) ** 2
            + cos($officeLatitudeRadians) * cos($affiliateLatitudeRadians) * sin($deltaLongitude / 2) ** 2;
        $angularDistance = 2 * asin(min(1, sqrt($haversineFormulaPart)));

        return self::EARTH_RADIUS_KM * $angularDistance;
    }
}

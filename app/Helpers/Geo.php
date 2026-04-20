<?php

namespace App\Helpers;

class Geo
{
    /**
     * Convert a geo_center string (e.g. "12.345,67.890") to an array [lat, lng]
     */
    public static function get_geo_array($geo_center): array
    {
        if (empty($geo_center)) {
            return [0, 0];
        }

        if (is_array($geo_center)) {
            return $geo_center;
        }

        $parts = array_map('trim', explode(',', $geo_center));

        return [
            (float) ($parts[0] ?? 0),
            (float) ($parts[1] ?? 0),
        ];
    }
}
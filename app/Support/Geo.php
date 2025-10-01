<?php

namespace App\Support;

class Geo {
    public static function distanceMeters($lat1,$lon1,$lat2,$lon2): float {
        $R = 6371000; // m
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
        return 2 * $R * asin(sqrt($a));
    }
}
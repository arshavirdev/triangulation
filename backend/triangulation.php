<?php
$earthR = 6371;

/**
 * Get desired point coordinates based on 3 sets of coordinates of reference points
 * Actually it's referred to Trilateration
 * @param float $latA
 * @param float $lanA
 * @param float $distA

 * @param float $latB
 * @param float $lanB
 * @param float $distB
 * 
 * @param float $latC
 * @param float $lanC
 * @param float $distC
 * @return array
 */
function getTriangulatePoint(
    float $latA, float $lonA, float $distA,
    float $latB, float $lonB, float $distB,
    float $latC, $lonC, $distC
): array {
    global $earthR;
    
    // Lat/Long -> radians
    // Lat/Long(radians) to ECEF (https://en.wikipedia.org/wiki/Earth-centered,_Earth-fixed_coordinate_system)
    $xA = $earthR * cos(deg2rad($latA)) * cos(deg2rad($lonA));
    $yA = $earthR * cos(deg2rad($latA)) * sin(deg2rad($lonA));
    $zA = $earthR * sin(deg2rad($latA));

    $xB = $earthR * cos(deg2rad($latB)) * cos(deg2rad($lonB));
    $yB = $earthR * cos(deg2rad($latB)) * sin(deg2rad($lonB));
    $zB = $earthR * sin(deg2rad($latB));

    $xC = $earthR * cos(deg2rad($latC)) * cos(deg2rad($lonC));
    $yC = $earthR * cos(deg2rad($latC)) * sin(deg2rad($lonC));
    $zC = $earthR * sin(deg2rad($latC));

    // Compose points
    $P1 = [$xA, $yA, $zA];
    $P2 = [$xB, $yB, $zB];
    $P3 = [$xC, $yC, $zC];

    $ex = [
        $P2[0] - $P1[0],
        $P2[1] - $P1[1],
        $P2[2] - $P1[2]
    ];
    $distP1P2 = sqrt(pow($ex[0], 2) + pow($ex[1], 2) + pow($ex[2], 2));
    $ex = [
        $ex[0] / $distP1P2,
        $ex[1] / $distP1P2,
        $ex[2] / $distP1P2
    ];

    $i = $ex[0] * ($P3[0] - $P1[0]) +
        $ex[1] * ($P3[1] - $P1[1]) +
        $ex[2] * ($P3[2] - $P1[2]);

    $ey = [
        $P3[0] - $P1[0] - $i * $ex[0],
        $P3[1] - $P1[1] - $i * $ex[1],
        $P3[2] - $P1[2] - $i * $ex[2]
    ];

    $ey = [$P3[0] - $P1[0] - $i * $ex[0], $P3[1] - $P1[1] - $i * $ex[1], $P3[2] - $P1[2] - $i * $ex[2]];
    $distP1P3 = sqrt(pow($ey[0], 2) + pow($ey[1], 2) + pow($ey[2], 2));
    $ey = [$ey[0] / $distP1P3, $ey[1] / $distP1P3, $ey[2] / $distP1P3];

    $ez = [
        $ex[1] * $ey[2] - $ex[2] * $ey[1],
        $ex[2] * $ey[0] - $ex[0] * $ey[2],
        $ex[0] * $ey[1] - $ex[1] * $ey[0]
    ];

    $P3P1 = sqrt(pow($P3[0] - $P1[0], 2) + pow($P3[1] - $P1[1], 2) + pow($P3[2] - $P1[2], 2));

    $x = ($distA * $distA - $distB * $distB + $distP1P2 * $distP1P2) / (2 * $distP1P2);
    $y = (($distA * $distA - $distC * $distC + $distP1P3 * $distP1P3) / (2 * $P3P1)) - (($i / $P3P1) * $x);
    $z = sqrt($distA * $distA - $x * $x - $y * $y);

    $triPt = [
        $P1[0] + $x * $ex[0] + $y * $ey[0] + $z * $ez[0],
        $P1[1] + $x * $ex[1] + $y * $ey[1] + $z * $ez[1],
        $P1[2] + $x * $ex[2] + $y * $ey[2] + $z * $ez[2]
    ];

    // XYZ -> Lat/Long
    $lat = rad2deg(asin($triPt[2] / $earthR));
    $lon = rad2deg(atan2($triPt[1], $triPt[0]));

    return [
        "lat" => $lat,
        "lon" => $lon
    ];
}

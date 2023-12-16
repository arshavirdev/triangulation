<?php
include './triangulation.php';

// Setup, Config
$latA = 50.110889;
$lonA = 8.682139;
$latB = 39.048111;
$lonB = -77.472806;
$latC = 45.849100;
$lonC = -119.714000;

// WORKING DATA
// $latA = 37.418436;
// $lonA = -121.963477;
// $latB = 37.417243;
// $lonB = -121.961889;
// $latC = 37.418692;
// $lonC = -121.960194;

// $distA = 0.265710701754
// $distB = 0.234592423446
// $distC = 0.0548954278262


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Validations
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'errorMessage' => 'Method Not Allowed'
    ]);
    exit();
}

$requiredParams = ['distA', 'distB', 'distC'];
foreach ($requiredParams as $param) {
    if (!isset($_GET[$param]) || empty($_GET[$param])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'errorMessage' => 'Missing or empty parameter: ' . $param
        ]);
        exit();
    }
}

// IF we will have STRING then + htmlspecialchars(...) for sec.
$distA = filter_input(INPUT_GET, 'distA', FILTER_VALIDATE_FLOAT);
$distB = filter_input(INPUT_GET, 'distB', FILTER_VALIDATE_FLOAT);
$distC = filter_input(INPUT_GET, 'distC', FILTER_VALIDATE_FLOAT);

// Will be good to have one iteration for all validation, but this one is more readable
foreach ([$distA, $distB, $distC] as $val) {
    if (!$val || $val < 0) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'errorMessage' => 'Invalid or negative distance'
        ]);
        exit();
    }
}

$coordinates = getTriangulatePoint($latA, $lonA, $distA, $latB, $lonB, $distB, $latC, $lonC, $distC);

$result = is_nan($coordinates['lat']) || is_nan($coordinates['lon'])
    ? 'Incorrect distances: desired point not found'
    : $coordinates['lat'] . ', ' . $coordinates['lon'];

echo json_encode([
    'status' => 'success',
    'result' => $result
]);
exit();

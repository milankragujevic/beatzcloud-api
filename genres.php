<?php 
error_reporting(0);

$config = json_decode(file_get_contents('config.json'), true);
$endpoint = $config['endpoint'];

$url = $endpoint . 'secure/genres/popular';

$api = json_decode(file_get_contents($url), true);

if(!$api) { exit; }

$genres = [];

foreach($api as $genre) {
	$genres[] = [
		'name' => ucwords($genre['name']),
		'popularity' => $genre['popularity'],
		'picture' => 'https://' . $_SERVER['HTTP_HOST'] . '/picture?url=' . rawurlencode(base64_encode(str_rot13(str_replace($endpoint, '%endpoint%', $genre['image']))))
	];
}

usort($genres, function($a, $b) {
	return strnatcmp($a['popularity'], $b['popularity']);
});

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode($genres);
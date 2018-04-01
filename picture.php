<?php
error_reporting(0);

$config = json_decode(file_get_contents('config.json'), true);
$endpoint = $config['endpoint'];

$url = str_rot13(base64_decode(rawurldecode($_GET['url'])));
$url = str_replace('%endpoint%', $endpoint, $url);

$cache = 'pictures/' . md5($url) . '.jpg';

if(!is_file($cache)) {
	$data = file_get_contents($url);
	$im = imagecreatefromstring($data);
	if(!$im) {
		file_put_contents($cache, '');
	} else {
		imagejpeg($im, $cache, 75);
		imagedestroy($im);
	}
}

if(filesize($cache) < 1) {
	exit;
}

header('Content-Type: image/jpeg');
header('Access-Control-Allow-Origin: *');
header('Content-Length: ' . filesize($cache));
readfile($cache);
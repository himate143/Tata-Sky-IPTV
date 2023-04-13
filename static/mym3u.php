<?php

// Load the M3U file from the url or file
//$m3uUrl = 'https://gist.githubusercontent.com/mahipat99/blablabla/raw/';
$m3uUrl = 'mym3u.txt';
$m3uData = file_get_contents($m3uUrl);

$lines = explode("\n", $m3uData);
$staticUrls = [];
$tvgId = '';
$licenseKey = '';
foreach ($lines as $line) {
    if (strpos($line, '#EXTINF') !== false) {
        preg_match('/tvg-id="(.*?)"/', $line, $matches);
        if (count($matches) > 1) {
            $tvgId = $matches[1];
        }
    } else if (strpos($line, '#KODIPROP:inputstream.adaptive.license_key') !== false) {
        preg_match('/#KODIPROP:inputstream.adaptive.license_key=(.*)/', $line, $matches);
        if (count($matches) > 1) {
            $licenseKey = $matches[1];
        }
    } else if (strpos($line, 'http') !== false) {
        if ($tvgId && $licenseKey) {
            $staticUrls[$tvgId] = $licenseKey;
            $tvgId = '';
            $licenseKey = '';
        }
    }
}

if(isset($_GET['tvg-id']) && $_GET['tvg-id'] != '') {
    if (isset($staticUrls[$_GET['tvg-id']])) {
        echo $staticUrls[$_GET['tvg-id']];
    } else {
        echo "Invalid tvg-id requested\n </br>";
    }
} else {
    foreach ($staticUrls as $tvgId => $licenseKey) {
	echo "Static URL for tvg-id $tvgId : $licenseKey\n </br>";
    }
}

?>

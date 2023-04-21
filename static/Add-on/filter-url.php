<?php

// Load the M3U file from the url or file
//$m3uUrl = 'https://gist.githubusercontent.com/mahipat99/blablabla/raw/';
$input_file = "mym3u.txt";
$playlist = file($input_file);
$output_file = "filter.txt";
$baseUrl = 'http://xyz.com/mym3u.php?tvg-id=';
$tvgFile = 'channels.txt';
$tvg_ids = file($tvgFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (file_exists($tvgFile)) {
    $tvgContents = file_get_contents($tvgFile);
    preg_match_all('/^\s*(\d+)\s+-\s+(.*)$/m', $tvgContents, $matches, PREG_SET_ORDER);
    $tvg_ids = array_map(function($match) {
        return $match[1];
    }, $matches);
} else {
    $tvg_ids = array();
}

if (empty($tvg_ids)) {
    $tvg_ids = preg_grep('/^#EXTINF.*tvg-id="(\d+)".*$/m', $playlist);
    $tvg_ids = array_map(function($line) {
        preg_match('/^#EXTINF.*tvg-id="(\d+)".*$/m', trim($line), $matches);
        return $matches[1];
    }, $tvg_ids);
}

$groups = array();
$currentGroup = '';

foreach ($playlist as $line) {
    if (strpos($line, 'group-title') !== false) {
        $groupName = substr($line, strpos($line, 'group-title=') + 13, -2);
        $currentGroup = $groupName;
    }

    if ($currentGroup !== '') {
        $groups[$currentGroup][] = $line;
    }
}

$output = '#EXTM3U x-tvg-url="https://www.tsepg.cf/epg.xml.gz"' . "\n\n";
$keep_lines = true;
$previous_line = "";

foreach ($tvg_ids as $tvg_id) {
    foreach ($groups as $groupName => $lines) {
        foreach ($lines as $key => $line) {
            if (strpos($line, "#EXTINF") !== false && strpos($line, "tvg-id=") !== false) {
                preg_match('/tvg-id="(\d+)"/', $line, $match);
                $tvg_id_line = $match[1];

                if ($tvg_id_line === $tvg_id) {
                    $keep_lines = true;
                } else {
                    $keep_lines = false;
                }
            }

            if ($keep_lines && strpos($line, "#KODIPROP:inputstream.adaptive.license_key") !== false) {
                preg_match('/https:\/\/.*getlicense\?(.*)/', $line, $match);
                $line = str_replace($match[0], $baseUrl . $tvg_id, $line);
            }

            if ($keep_lines) {
                if (strpos($previous_line, "#EXTINF") !== false && strpos($previous_line, "tvg-id=") !== false) {
                    $output .= $previous_line . "\n";
                }
                $output .= $line;
            }
        }
    }
}

file_put_contents($output_file, trim($output));

echo "Output saved to $output_file";

?>

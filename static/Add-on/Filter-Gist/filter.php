<?php

$input_file = "mym3u.txt";
$output_file = "filter.txt";
$playlist = file($input_file);
$tvgFile = 'channels.txt';
$tvg_ids = file($tvgFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$access_token = 'ghp_blablabla';
$api = 'https://api.github.com';
$gist_id = '13000000000000000000'; // replace with your gist ID
$url = $api . '/gists/' . $gist_id;
$list = 'playlist.txt'; // gist file name

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
// prepare the body data for gist
$data = json_encode(array(
    'files' => array(
        $list => array(
            "content" => file_get_contents($output_file)
        )
    )
));

$options = array(
    'http' => array(
        'method' => 'PATCH',
        'header' => array(
            'Authorization: token ' . $access_token,
            'Content-Type: application/json',
            'User-Agent: PHP'
        ),
        'content' => $data
    )
);
$context = stream_context_create($options);

$response = file_get_contents($url, false, $context);

if (isset($http_response_header[0]) && ($http_response_header[0] == 'HTTP/1.1 200 OK' || $http_response_header[0] == 'HTTP/1.1 201 Created')) {

    echo 'Gist updated successfully!';
} else {
    echo 'Error updating gist.';
}
?>

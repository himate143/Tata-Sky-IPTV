<?php
// download the file or use url
//$input_file = "https://gist.githubusercontent.com/mahipat99/blablabla/raw";
$input_file = "mym3u.txt";
$output_file = "filter.txt";
$playlist = file($input_file);
$tvgFile = 'channels.txt';
$tvg_ids = file($tvgFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$replace_file = "replace.txt";
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

$tvg_lines = [];
foreach ($playlist as $key => $line) {
    if (strpos($line, "#EXTINF") !== false && strpos($line, "tvg-id=") !== false) {
        preg_match('/tvg-id="(\d+)"/', $line, $match);
        $tvg_id = $match[1];
        $tvg_lines[$tvg_id][] = $key;
    }
}

$replace = array();

if (file_exists($replace_file) && $replace_contents = file_get_contents($replace_file)) {
    preg_match_all('/^\s*(\S+)\s*=\s*(\S+)\s*$/m', $replace_contents, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $replace[$match[1]] = $match[2];
    }
}

foreach ($playlist as &$line) {
    foreach ($replace as $search => $replacement) {
        $line = str_replace($search, $replacement, $line);
    }
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
$added_urls = array();

foreach ($tvg_ids as $tvg_id) {
    foreach ($groups as $group_name => $lines) {
        $add_line = false;
        foreach ($lines as $key => $line) {
            if (strpos($line, "#EXTINF") !== false && strpos($line, "tvg-id=") !== false) {
			preg_match('/tvg-id="(\d+)"/', $line, $match);
			$add_line = ($match[1] === $tvg_id) ? true : false;
			}

            if ($add_line) {
                if (strpos($line, "http") !== false) {
                    if (!in_array($line, $added_urls)) {
                        $output .= $line;
                        $added_urls[] = $line;
                    }
                } else {
                    $output .= $line;
                }
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
    // unlink($output_file); // delete file after gist upload
if (isset($http_response_header[0]) && ($http_response_header[0] == 'HTTP/1.1 200 OK' || $http_response_header[0] == 'HTTP/1.1 201 Created')) {

    echo 'Gist updated successfully!';
} else {
    echo 'Error updating gist.';
}
?>

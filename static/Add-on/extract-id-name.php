<?php
// download the file or use url
//$m3u_file = "https://gist.githubusercontent.com/mahipat99/blablabla/raw/";
$m3u_file = "mym3u.txt";
$id_file = "id.txt";
$name_file = "name.txt";
$merged_file = "id-name.txt";

$m3u_contents = file_get_contents($m3u_file);

// Extracting IDs
preg_match_all('/tvg-id="(\d+)"/', $m3u_contents, $id_matches);
$id_values = implode("\n", $id_matches[1]);
if (file_put_contents($id_file, $id_values)) {
    echo "Success: tvg-id values extracted and saved to id.txt\n";
} else {
    echo "Fail: Unable to save tvg-id values to id.txt\n";
}

// Extracting names
$file = fopen($m3u_file, "r");
$output = fopen($name_file, "w");
if ($file) {
    while (($line = fgets($file)) !== false) {
        if (strpos($line, "group-title=") !== false) {
            $channel = trim(strstr($line, ','));
            $channel = substr($channel, 1);
            $channel = ltrim($channel);
            fwrite($output, $channel . "\n");
        }
    }
    fclose($file);
    fclose($output);
    echo "</br>Success: Channel names extracted and saved to name.txt!\n";
} else {
    echo "Error opening file.\n";
}

// Merging IDs and names
$merged_output = fopen($merged_file, "w");
if ($merged_output) {
    $id_array = explode("\n", $id_values);
    $name_array = file($name_file, FILE_IGNORE_NEW_LINES);
    foreach ($id_array as $key => $id) {
        fwrite($merged_output, $id . " - " . $name_array[$key] . "\n");
    }
    fclose($merged_output);
    echo "</br>Success: ID and name values merged and saved to id_name.txt!\n";
} else {
    echo "</br>Error opening file.\n";
}
?>

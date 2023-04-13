<?php

$retryCount = 0;
$retryLimit = 10;

while ($retryCount < $retryLimit) {
  $url = 'https://gist.githubusercontent.com/mahipat99/blablabla/raw/';
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  $data = curl_exec($ch);
  $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  
  curl_close($ch);
  
  if ($statusCode == 200) {
    file_put_contents('mym3u.txt', $data);
    echo "File downloaded successfully!\n";
    break;
  } else {
    $retryCount++;
    echo "Retrying... (attempt $retryCount)\n";
    sleep(1); // wait for 1 second before retrying
  }
}

?>

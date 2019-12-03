<?php
echo 'hello';

try {
$ch = curl_init("https://qatrack.epa.gov/sop_tab/feed/");
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$fp = fopen("example_homepage.txt", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_SSLVERSION,3);  
curl_exec($ch);
curl_close($ch);
fclose($fp);
echo 'close';
}
catch(Exception $e) {
      var_dump($e);
}
?>
<?php
$url = 'https://curl.se/ca/cacert.pem';
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  
$response = file_get_contents($url, false, stream_context_create($arrContextOptions));
if ($response !== false) {
    file_put_contents('D:\xampp\php\extras\ssl\cacert.pem', $response);
    echo "Certificate updated successfully.\n";
} else {
    echo "Failed to download certificate.\n";
}

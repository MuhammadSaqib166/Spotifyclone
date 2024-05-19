<?php
require_once 'config.php';

function getSpotifyAccessToken($client_id, $client_secret) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    
    $headers = array();
    $headers[] = 'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret);
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    
    $result = json_decode($result);
    return $result->access_token;
}

// Fetch and return the access token
$access_token = getSpotifyAccessToken($client_id, $client_secret);
?>

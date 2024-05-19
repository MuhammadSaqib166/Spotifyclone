<?php
// spotify_search.php

function getSpotifyGenres($access_token) {
    $url = 'https://api.spotify.com/v1/recommendations/available-genre-seeds?locale=el';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $headers = array();
    $headers[] = 'Authorization: Bearer ' . $access_token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    
    return json_decode($result);
}
function getSpotifyFeaturedPlaylists($access_token) {
    $url = 'https://api.spotify.com/v1/browse/featured-playlists?locale=el';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = array();
    $headers[] = 'Authorization: Bearer ' . $access_token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        return null; // Return null to indicate an error
    }
    curl_close($ch);

    $response = json_decode($result);
    if (isset($response->error)) {
        echo 'Error: ' . $response->error->message;
        return null; // Return null if there's an error in the response
    }

    // Return only the playlists array from the response
    return $response->playlists->items;
}
function getSpotifyPlaylistsByGenre($genre, $access_token) {
    $url = 'https://api.spotify.com/v1/browse/categories/' . urlencode($genre) . '/playlists?locale=el';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = array();
    $headers[] = 'Authorization: Bearer ' . $access_token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        return null; // Return null to indicate an error
    }
    curl_close($ch);

    $response = json_decode($result);
    if (isset($response->error)) {
        echo 'Error: ' . $response->error->message;
        return null; // Return null if there's an error in the response
    }

    // Return only the playlists array from the response
    return $response->playlists->items;
}
function searchSpotifySongs($query, $access_token, $genre = null, $playlist = null) {
    $url = 'https://api.spotify.com/v1/search?type=track&q=' . urlencode($query);
    
    // If genre is specified, include it in the search query
    if ($genre !== null) {
        $url .= '%20genre:' . urlencode($genre);
    }

    // If playlist is specified, include it in the search query
    if ($playlist !== null) {
        $url .= '%20playlist:' . urlencode($playlist);
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $headers = array();
    $headers[] = 'Authorization: Bearer ' . $access_token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    
    return json_decode($result);
}
?>

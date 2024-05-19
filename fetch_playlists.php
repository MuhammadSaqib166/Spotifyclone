<?php
// Include the required files
require_once 'spotify_token.php';
require_once 'spotify_search.php';

// Get the access token from the included file
$access_token = $access_token;

// Get the selected genre from the query parameters
$selected_genre = isset($_GET['genre']) ? $_GET['genre'] : '';

// Fetch playlists based on the selected genre
$playlists = getSpotifyPlaylistsByGenre($selected_genre, $access_token);

// Display playlists
if ($playlists !== null && !empty($playlists)) {
    // Dropdown options for playlists
    foreach ($playlists as $playlist) {
        echo '<option value="' . htmlspecialchars($playlist->name) . '">' . htmlspecialchars($playlist->name) . '</option>';
    }
} else {
    echo '<option value="">No playlists found</option>';
}
?>

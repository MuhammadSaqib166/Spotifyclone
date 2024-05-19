<?php
// Include the required files
require_once 'spotify_token.php';
require_once 'spotify_search.php';

// Get the access token from the included file
$access_token = $access_token;

// Fetch genres
$genres = getSpotifyGenres($access_token);
$selected_genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$selected_playlist = isset($_GET['playlist']) ? $_GET['playlist'] : '';


// Debugging output
// echo "Selected Genre: " . $selected_genre . "<br>";
// echo "Selected Playlist: " . $selected_playlist . "<br>";

// Check if the query is not empty
if (!empty($_GET['query'])) {
    $query = $_GET['query']; // Retrieve the search query from the form submission

    // Search for songs, including the selected genre and playlist parameters
    $search_results = searchSpotifySongs($query, $access_token, $selected_genre, $selected_playlist);

    // Display the results
    if (!empty($search_results->tracks->items)) {
        echo '<div style="display: flex;">';
        
        // Left side: List of search results
        echo '<div style="flex: 1;">';
        foreach ($search_results->tracks->items as $track) {
            echo '<div style="margin-bottom: 10px;">';
            echo '<strong>Track:</strong> ' . $track->name . '<br>';
            echo '<strong>Artist:</strong> ' . $track->artists[0]->name . '<br>';
            echo '<strong>Album:</strong> ' . $track->album->name . '<br>';
            echo 'Preview URL: <a href="' . $track->preview_url . '">Listen</a><br>';
            echo '</div>';
        }
        echo '</div>';
        
        // Right side: Details of the first result
        $first_track = $search_results->tracks->items[0];
        echo '<div style="flex: 1; padding-left: 20px;">';
        if (!empty($first_track->album->images)) {
            echo '<img src="' . $first_track->album->images[0]->url . '" alt="Album Art" style="width: 100%; max-width: 300px;">';
        } else {
            echo '<img src="placeholder-image-url.jpg" alt="No Album Art" style="width: 100%; max-width: 300px;">';
        }
        echo '<p><strong>Song Title:</strong> ' . $first_track->name . '</p>';
        echo '<p><strong>By Artist:</strong> ' . $first_track->artists[0]->name . '</p>';
        echo '</div>';
        
        echo '</div>';
    } else {
        echo 'No results found.';
    }
}
?>

<!-- HTML form to search for songs -->
<form id="searchForm" method="GET" action="index.php">
    <label for="genre">Genre:</label>
    <select name="genre" id="genre">
        <?php
        if (!empty($genres->genres)) {
            foreach ($genres->genres as $genre) {
                echo '<option value="' . $genre . '"' . ($selected_genre == $genre ? ' selected' : '') . '>' . ucfirst($genre) . '</option>';
            }
        }
        ?>
    </select>
    <br>
    <label for="playlist">Playlists:</label>
    <select name="playlist" id="playlist">
        <!-- Playlist options will be populated dynamically based on the selected genre -->
    </select>
    <br>
    <input type="text" name="query" id="query" placeholder="Search for a song" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
    <input type="submit" name="submit" value="Search">
</form>

<script>
    // Function to perform the search asynchronously
    function search(query) {
        // Get the selected genre and playlist
        var genre = document.getElementById('genre').value;
        var playlist = document.getElementById('playlist').value;

        // Perform the search using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'index.php?genre=' + encodeURIComponent(genre) + '&playlist=' + encodeURIComponent(playlist) + '&query=' + encodeURIComponent(query), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Update the search results container with the response
                document.getElementById('searchResults').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Fetch playlists based on the selected genre
    function fetchPlaylists() {
        var genre = document.getElementById('genre').value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_playlists.php?genre=' + encodeURIComponent(genre), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Update the playlists dropdown with the fetched playlists
                document.getElementById('playlist').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Add event listener to genre dropdown
    document.getElementById('genre').addEventListener('change', function() {
        // Fetch playlists when genre selection changes
        fetchPlaylists();
    });

    // Fetch playlists initially if a genre is selected
    if (document.getElementById('genre').value !== '') {
        fetchPlaylists();
    }
</script>

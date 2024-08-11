<?php
include 'song_tree.php';

$conn = new mysqli('localhost', 'root', '', 'song_db');

$order = $_GET['order'];

$result = $conn->query("SELECT * FROM songs");

$song_bst = new SongBST();

while ($row = $result->fetch_assoc()) {
    $song_bst->insert($row['file_name'], $row['preference'], $row['date_added'], $row['file_path']);
}

if ($order == 'preference') {
    $songs = $song_bst->inOrderPreferenceRange(0,10);
} else if($order == 'date') {
    $songs = $song_bst->inOrderDate();
} else {
    $songs = $song_bst->inOrderPreferenceRange(7,10);
}


if (count($songs) == 0) {
    echo "<p>No songs to show.</p>";
} else {
    echo "<html>";
    echo "<head>";
    echo '<link rel="stylesheet" href="style.css">';
    echo "</head>";
    echo "<ul class='playlist'>";
foreach ($songs as $index => $song) {
    echo "<li class='playlist-item'>
            <div class='song-info'>
                <span class='song-name'>{$song['name']}</span>
                <span class='song-preference'>{$song['preference']} stars</span>
                <span class='song-date'>Added on {$song['date_added']}</span>
            </div>
            <audio class='song-audio' id='audio-$index' controls>
                <source src='{$song['file_path']}' type='audio/mpeg'>
                Your browser does not support the audio element.
            </audio>
          </li>";
}
echo "</ul>";
echo '<div class="button-container">';
echo '<button onclick="playPreviousSong()">Previous</button>';
echo '<button onclick="pauseCurrentSong()">Pause</button>';
echo '<button onclick="playNextSong()">Next</button>';
echo '</div>';
echo "</html>";

}

$conn->close();
?>

<script>
class Node {
    constructor(value) {
        this.value = value;
        this.next = null;
    }
}

class LinkedListStack {
    constructor() {
        this.top = null;
        this.size = 0;
    }

    push(value) {
        const newNode = new Node(value);
        if (this.size === 0) {
            this.top = newNode;
        } else {
            newNode.next = this.top;
            this.top = newNode;
        }
        this.size++;
    }

    pop() {
        if (this.size === 0) {
            return null;
        }
        const poppedValue = this.top.value;
        this.top = this.top.next;
        this.size--;
        return poppedValue;
    }

    peek() {
        return this.size > 0 ? this.top.value : null;
    }

    isEmpty() {
        return this.size === 0;
    }
}

let playedStack = new LinkedListStack();
const songs = Array.from(document.querySelectorAll('audio'));
let currentSong = null;

// Function to play the next song
function playNextSong() {
    if (currentSong) {
        playedStack.push(currentSong);
        currentSong.pause();
    }
    currentSong = playedStack.size < songs.length ? songs[playedStack.size] : null;
    if (currentSong) {
        currentSong.play();
    }
}

// Function to play the previous song
function playPreviousSong() {
    if (!playedStack.isEmpty()) {
        if (currentSong) {
            currentSong.pause();
        }
        currentSong = playedStack.pop();
        if (currentSong) {
            currentSong.play();
        }
    }
}

// Function to pause the current song
function pauseCurrentSong() {
    if (currentSong) {
        currentSong.pause();
    }
}

// Initialize the playlist
songs.forEach((song, index) => {
    song.addEventListener('play', function() {
        // When a song starts playing, set it as the current song
        currentSong = song;
    });

    song.addEventListener('ended', playNextSong);
});
</script>

<!-- Add Previous, Next, and Pause buttons -->

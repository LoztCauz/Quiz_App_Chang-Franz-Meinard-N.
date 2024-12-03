<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";
$port = "3307";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT username, score FROM leaderboard ORDER BY score DESC, username ASC LIMIT 10");

echo "<h1>Leaderboard</h1>";
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Score</th>
            </tr>";
    $rank = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$rank}</td>
                <td>{$row['username']}</td>
                <td>{$row['score']}</td>
              </tr>";
        $rank++;
    }
    echo "</table>";
} else {
    echo "No scores yet!";
}
$conn->close();
?>
<a href="index.php">Back to Quiz</a>

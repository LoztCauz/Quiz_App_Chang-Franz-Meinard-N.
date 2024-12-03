<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";
$port = "3307";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch questions from the database
$sql = "SELECT * FROM math_questions";
$result = $conn->query($sql);

$questions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}

// Initialize score
$score = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    foreach ($questions as $index => $question) {
        if (isset($_POST["question{$question['id']}"]) &&
            $_POST["question{$question['id']}"] == $question['correct_option']) {
            $score++;
        }
    }

    // Save the score to the leaderboard
    $stmt = $conn->prepare("INSERT INTO leaderboard (username, score) VALUES (?, ?)");
    $stmt->bind_param("si", $username, $score);
    $stmt->execute();

    echo "<h2>Your Score: $score/" . count($questions) . "</h2>";
    echo '<a href="index.php">Try Again</a>';
    echo '<a href="leaderboard.php">View Leaderboard</a>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Quiz</title>
</head>
<body>
    <h1>Math Quiz</h1>
    <form method="post" action="">
        <label for="username">Enter your name:</label>
        <input type="text" id="username" name="username" required><br><br>
        <?php foreach ($questions as $question): ?>
            <fieldset>
                <legend><?php echo $question['question']; ?></legend>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <label>
                        <input type="radio" name="question<?php echo $question['id']; ?>" value="<?php echo $i; ?>">
                        <?php echo $question["option$i"]; ?>
                    </label><br>
                <?php endfor; ?>
            </fieldset>
        <?php endforeach; ?>
        <input type="submit" value="Submit">
    </form>
</body>
</html>

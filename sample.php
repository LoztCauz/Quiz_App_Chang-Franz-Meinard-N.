<?php
$servername = "localhost";
$username = "root";      // MySQL username
$password = "";          // MySQL password
$dbname = "quiz_app";    // Database name
$port = "3307";          // Port number

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define questions and answers
$questions = [
    [
        "question" => "What does PHP stand for?",
        "options" => ["Personal Home Page", "Private Home Page", "PHP: Hypertext Preprocessor", "Public Hypertext Preprocessor"],
        "answer" => 2
    ],
    [
        "question" => "Which symbol is used to access a property of an object in PHP?",
        "options" => [".", "->", "::", "#"],
        "answer" => 1
    ],
    [
        "question" => "Which function is used to include a file in PHP?",
        "options" => ["include()", "require()", "import()", "load()"],
        "answer" => 0
    ],
    [
        "question" => "What is the value of π (pi) approximately?",
        "options" => ["3.14", "2.71", "1.61", "3.14159"],
        "answer" => 3
    ],
    [
        "question" => "Solve: 7 × 6 = ?",
        "options" => ["42", "36", "49", "48"],
        "answer" => 0
    ],
    [
        "question" => "What is the square root of 144?",
        "options" => ["10", "11", "12", "13"],
        "answer" => 2
    ],
    [
        "question" => "What is the result of 15 ÷ 3?",
        "options" => ["3", "4", "5", "6"],
        "answer" => 2
    ],
    [
        "question" => "What is the area of a circle with radius 7? (Use π ≈ 3.14)",
        "options" => ["153.86", "150.72", "140.50", "154.00"],
        "answer" => 0
    ],
    [
        "question" => "Solve for x: 2x + 5 = 13.",
        "options" => ["4", "3", "5", "6"],
        "answer" => 0
    ],
    [
        "question" => "What is the perimeter of a rectangle with sides 8 and 5?",
        "options" => ["26", "40", "20", "30"],
        "answer" => 0
    ],
    // New math questions
    [
        "question" => "What is the square of 15?",
        "options" => ["225", "250", "2250", "150"],
        "answer" => 0
    ],
    [
        "question" => "What is the result of 100 - 25 × 2?",
        "options" => ["50", "75", "70", "60"],
        "answer" => 0
    ],
    [
        "question" => "What is the next number in the sequence: 2, 4, 8, 16, ...?",
        "options" => ["32", "24", "18", "30"],
        "answer" => 0
    ],
    [
        "question" => "If a car travels 60 km in one hour, how long will it take to travel 180 km?",
        "options" => ["3 hours", "2 hours", "1 hour", "4 hours"],
        "answer" => 0
    ]
];
// Initialize score
$score = 0;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($questions as $index => $question) {
        if (isset($_POST["question$index"]) && $_POST["question$index"] == $question['answer']) {
            $score++;
        }
    }

    // Store score in database
    $name = $conn->real_escape_string($_POST['username']);
    $stmt = $conn->prepare("INSERT INTO leaderboard (username, score) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $score);
    $stmt->execute();
    $stmt->close();

    echo "<h2>Your Score: $score/" . count($questions) . "</h2>";
    echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">Try Again</a><br>'; // Redirect to the same script
    echo '<a href="leaderboard.php">View Leaderboard</a>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP & Math Quiz</title>
</head>
<body>
    <h1>PHP & Math Quiz</h1>
    <form method="post" action="">
        <label for="username">Enter your name:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <?php foreach ($questions as $index => $question): ?>
            <fieldset>
                <legend><?php echo htmlspecialchars($question['question']); ?></legend>
                <?php foreach ($question['options'] as $optionIndex => $option): ?>
                    <label>
                        <input type="radio" name="question<?php echo $index; ?>" value="<?php echo $optionIndex; ?>" required>
                        <?php echo htmlspecialchars($option); ?>
                    </label><br>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>
        <input type="submit" value="Submit">
    </form>
</body>
</html>

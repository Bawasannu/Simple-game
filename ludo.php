<?php
session_start();

// Initialize the game
if (!isset($_SESSION['positions'])) {
    $_SESSION['positions'] = [0, 0]; // Player 1, Player 2
    $_SESSION['turn'] = 0; // 0 = Player 1, 1 = Player 2
    $_SESSION['message'] = "Game started! Player 1's turn.";
}

if (isset($_POST['roll'])) {
    $roll = rand(1, 6);
    $_SESSION['positions'][$_SESSION['turn']] += $roll;

    if ($_SESSION['positions'][$_SESSION['turn']] >= 15) {
        $_SESSION['message'] = "Player " . ($_SESSION['turn'] + 1) . " wins with roll $roll!";
        $_SESSION['positions'] = [0, 0]; // Reset
        $_SESSION['turn'] = 0;
    } else {
        $_SESSION['message'] = "Player " . ($_SESSION['turn'] + 1) . " rolled a $roll.";
        $_SESSION['turn'] = 1 - $_SESSION['turn']; // Switch turn
    }
}

if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: ludo.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Ludo in PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>PHP Ludo (Simplified)</h1>
    <div class="board">
        <?php
        for ($i = 0; $i < 16; $i++) {
            $class = 'cell';
            if ($i == $_SESSION['positions'][0]) $class .= ' player1';
            if ($i == $_SESSION['positions'][1]) $class .= ' player2';
            echo "<div class='$class'>" . ($i + 1) . "</div>";
        }
        ?>
    </div>

    <form method="post">
        <button name="roll" type="submit">Roll Dice</button>
        <button name="reset" type="submit">Reset Game</button>
    </form>

    <p><?php echo $_SESSION['message']; ?></p>
    <p>Current Turn: Player <?php echo $_SESSION['turn'] + 1; ?></p>

    <footer>
        Built by <strong>Bawasannu</strong>
    </footer>
</body>
</html>
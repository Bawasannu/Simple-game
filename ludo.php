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

    // Capture logic
    $opponent_turn = 1 - $_SESSION['turn'];
    $current_player_pos = $_SESSION['positions'][$_SESSION['turn']];
    $opponent_player_pos = $_SESSION['positions'][$opponent_turn];

    // Check if the current player landed on the opponent's cell
    // and it's not the starting cell (0) or the winning cell (>=15)
    if ($current_player_pos == $opponent_player_pos && $current_player_pos > 0 && $current_player_pos < 15) {
        $_SESSION['positions'][$opponent_turn] = 0; // Send opponent back to start
        $_SESSION['message'] = "Player " . ($_SESSION['turn'] + 1) . " rolled a $roll and captured Player " . ($opponent_turn + 1) . "!"; // Update message
    } else {
        // Only set the basic roll message if no capture happened
        $_SESSION['message'] = "Player " . ($_SESSION['turn'] + 1) . " rolled a $roll.";
    }


    if ($_SESSION['positions'][$_SESSION['turn']] >= 15) {
        // Ensure win message overrides capture message if win occurs on the same turn
        $_SESSION['message'] = "Player " . ($_SESSION['turn'] + 1) . " wins with roll $roll!";
        $_SESSION['positions'] = [0, 0]; // Reset
        $_SESSION['turn'] = 0;
    } else if (!($current_player_pos == $opponent_player_pos && $current_player_pos > 0 && $current_player_pos < 15)) {
        // Only switch turn if no win AND no capture happened on a non-starting/winning square
        // If a capture happened, the message is already set, just switch turn
        $_SESSION['turn'] = 1 - $_SESSION['turn']; // Switch turn
    } else {
        // If capture happened, just switch turn (message already set)
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
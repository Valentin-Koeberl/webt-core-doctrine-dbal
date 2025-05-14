<?php
$host = 'localhost';
$dbname = 'usarps_championship';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("
        SELECT * FROM game_rounds 
        ORDER BY round_number ASC, played_at ASC
    ");
    $gameRounds = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USARPS Championship 2024</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f5f5f7;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 14px;
        }

        .header h1 {
            font-size: 2.8em;
            margin-bottom: 15px;
            color: #1d1d1f;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: 1.3em;
            color: #86868b;
            font-weight: 400;
        }

        .game-rounds {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .game-round {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 14px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .game-round:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.9);
        }

        .round-header {
            padding: 15px 20px;
            background: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .round-number {
            color: #1d1d1f;
            font-size: 1.1em;
            font-weight: 500;
        }

        .players-container {
            display: flex;
            padding: 20px;
            gap: 20px;
        }

        .player-info {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .player-name {
            font-weight: 500;
            font-size: 1.2em;
            color: #1d1d1f;
            margin-bottom: 8px;
        }

        .symbol {
            display: inline-block;
            padding: 6px 12px;
            background: #f5f5f7;
            color: #1d1d1f;
            border-radius: 8px;
            margin: 8px 0;
            font-weight: 500;
            font-size: 0.95em;
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .timestamp {
            color: #86868b;
            font-size: 0.9em;
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 15px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 2em;
            }

            .players-container {
                flex-direction: column;
                padding: 15px;
                gap: 10px;
            }

            .player-info {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>USARPS Championship 2024</h1>
            <p>Tournament Date: March 15, 2024</p>
        </div>

        <div class="game-rounds">
            <?php
            $currentRound = null;
            $roundPlayers = [];
            
            foreach ($gameRounds as $round) {
                if ($currentRound !== $round['round_number']) {
                    if ($currentRound !== null) {
                        echo '<div class="game-round">';
                        echo '<div class="round-header">';
                        echo '<div class="round-number">Round ' . htmlspecialchars($currentRound) . '</div>';
                        echo '</div>';
                        echo '<div class="players-container">';
                        foreach ($roundPlayers as $player) {
                            echo '<div class="player-info">';
                            echo '<div class="player-name">' . htmlspecialchars($player['player_name']) . '</div>';
                            echo '<div class="symbol">' . htmlspecialchars($player['symbol']) . '</div>';
                            echo '<div class="timestamp">' . date('F j, Y - g:i A', strtotime($player['played_at'])) . '</div>';
                            echo '</div>';
                        }
                        echo '</div></div>';
                    }
                    $currentRound = $round['round_number'];
                    $roundPlayers = [];
                }
                $roundPlayers[] = $round;
            }
            
            if ($currentRound !== null) {
                echo '<div class="game-round">';
                echo '<div class="round-header">';
                echo '<div class="round-number">Round ' . htmlspecialchars($currentRound) . '</div>';
                echo '</div>';
                echo '<div class="players-container">';
                foreach ($roundPlayers as $player) {
                    echo '<div class="player-info">';
                    echo '<div class="player-name">' . htmlspecialchars($player['player_name']) . '</div>';
                    echo '<div class="symbol">' . htmlspecialchars($player['symbol']) . '</div>';
                    echo '<div class="timestamp">' . date('F j, Y - g:i A', strtotime($player['played_at'])) . '</div>';
                    echo '</div>';
                }
                echo '</div></div>';
            }
            ?>
        </div>
    </div>
</body>
</html> 
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

$config = require __DIR__ . '/database.php';

$message = '';
$messageType = '';
$formData = [
    'player_name' => '',
    'symbol' => '',
    'round_number' => '',
    'played_at' => date('Y-m-d\TH:i')
];

try {
    $connection = DriverManager::getConnection($config);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData = [
            'player_name' => trim($_POST['player_name'] ?? ''),
            'symbol' => $_POST['symbol'] ?? '',
            'round_number' => (int)($_POST['round_number'] ?? 0),
            'played_at' => $_POST['played_at'] ?? ''
        ];

        if (empty($formData['player_name'])) {
            throw new Exception('Player name is required');
        }
        if (empty($formData['symbol'])) {
            throw new Exception('Symbol is required');
        }
        if ($formData['round_number'] <= 0) {
            throw new Exception('Round number must be greater than 0');
        }
        if (empty($formData['played_at'])) {
            throw new Exception('Date and time are required');
        }

        $connection->insert('game_rounds', [
            'player_name' => $formData['player_name'],
            'symbol' => $formData['symbol'],
            'round_number' => $formData['round_number'],
            'played_at' => $formData['played_at']
        ]);

        $message = 'Game round successfully added!';
        $messageType = 'success';
        
        $formData = [
            'player_name' => '',
            'symbol' => '',
            'round_number' => $connection->fetchOne('SELECT COALESCE(MAX(round_number), 0) + 1 FROM game_rounds'),
            'played_at' => date('Y-m-d\TH:i')
        ];
    } else {
        $formData['round_number'] = $connection->fetchOne('SELECT COALESCE(MAX(round_number), 0) + 1 FROM game_rounds');
    }

} catch (Exception $e) {
    $message = 'Error: ' . $e->getMessage();
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Game Round - USARPS Championship 2024</title>
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
            max-width: 800px;
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

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #1d1d1f;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 1em;
            color: #1d1d1f;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #007AFF;
            box-shadow: 0 0 0 2px rgba(0, 122, 255, 0.1);
        }

        button {
            background: #007AFF;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        button:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .message {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            font-weight: 500;
        }

        .message.success {
            background: rgba(52, 199, 89, 0.1);
            color: #34C759;
        }

        .message.error {
            background: rgba(255, 59, 48, 0.1);
            color: #FF3B30;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007AFF;
            text-decoration: none;
            font-weight: 500;
            text-align: center;
            width: 100%;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Add Game Round</h1>
        </div>

        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="player_name">Player Name</label>
                <input type="text" 
                       id="player_name" 
                       name="player_name" 
                       value="<?php echo htmlspecialchars($formData['player_name']); ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="symbol">Symbol</label>
                <select id="symbol" name="symbol" required>
                    <option value="">Select a symbol</option>
                    <option value="Rock" <?php echo $formData['symbol'] === 'Rock' ? 'selected' : ''; ?>>Rock</option>
                    <option value="Paper" <?php echo $formData['symbol'] === 'Paper' ? 'selected' : ''; ?>>Paper</option>
                    <option value="Scissors" <?php echo $formData['symbol'] === 'Scissors' ? 'selected' : ''; ?>>Scissors</option>
                </select>
            </div>

            <div class="form-group">
                <label for="round_number">Round Number</label>
                <input type="number" 
                       id="round_number" 
                       name="round_number" 
                       value="<?php echo htmlspecialchars($formData['round_number']); ?>" 
                       required 
                       min="1">
            </div>

            <div class="form-group">
                <label for="played_at">Date and Time</label>
                <input type="datetime-local" 
                       id="played_at" 
                       name="played_at" 
                       value="<?php echo htmlspecialchars($formData['played_at']); ?>"
                       required>
            </div>

            <button type="submit">Add Game Round</button>
        </form>

        <a href="../US4/index.php" class="back-link">← Back to Game Rounds</a>
    </div>
</body>
</html> 
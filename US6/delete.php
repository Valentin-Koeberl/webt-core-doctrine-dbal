<?php
require_once __DIR__ . '/../US5/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

$config = require __DIR__ . '/../US5/config/database.php';
$message = '';

try {
    $connection = DriverManager::getConnection($config);

    // Handle deletion
    if (isset($_GET['delete_id'])) {
        $deleteId = (int)$_GET['delete_id'];
        $deleted = $connection->delete('game_rounds', ['id' => $deleteId]);
        if ($deleted) {
            $message = 'Record deleted successfully!';
        } else {
            $message = 'Error: Record could not be deleted.';
        }
    }

    // Fetch all game rounds
    $rounds = $connection->fetchAllAssociative('SELECT * FROM game_rounds ORDER BY played_at DESC');

} catch (Exception $e) {
    $message = 'Error: ' . $e->getMessage();
    $rounds = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Game Rounds - USARPS Championship 2024</title>
    <style>
        body {
            background: #f5f5f7;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: rgba(255,255,255,0.85);
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            padding: 32px;
        }
        h1 {
            text-align: center;
            margin-bottom: 32px;
            color: #1d1d1f;
        }
        .message {
            margin-bottom: 24px;
            padding: 14px;
            border-radius: 8px;
            font-weight: 500;
            background: rgba(255, 59, 48, 0.1);
            color: #FF3B30;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }
        th {
            background: rgba(0,122,255,0.08);
            color: #1d1d1f;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .delete-btn {
            background: #FF3B30;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.2s;
        }
        .delete-btn:hover {
            background: #c8231a;
        }
        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: #007AFF;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 700px) {
            .container { padding: 12px; }
            th, td { padding: 8px 4px; font-size: 0.95em; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Delete Game Rounds</h1>
    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (count($rounds) === 0): ?>
        <p>No game rounds found.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Player Name</th>
                <th>Symbol</th>
                <th>Round</th>
                <th>Date/Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rounds as $round): ?>
            <tr>
                <td><?php echo htmlspecialchars($round['id']); ?></td>
                <td><?php echo htmlspecialchars($round['player_name']); ?></td>
                <td><?php echo htmlspecialchars($round['symbol']); ?></td>
                <td><?php echo htmlspecialchars($round['round_number']); ?></td>
                <td><?php echo htmlspecialchars($round['played_at']); ?></td>
                <td>
                    <form method="get" action="" onsubmit="return confirm('Are you sure you want to delete this record?');" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $round['id']; ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    <a href="../US4/index.php" class="back-link">← Back to Add Game Round</a>
</div>
</body>
</html> 
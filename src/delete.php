<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Exception;

$config = require __DIR__ . '/database.php';
$message = '';

try {
    $connection = DriverManager::getConnection($config);

    if (isset($_GET['delete_id'])) {
        $deleteId = (int)$_GET['delete_id'];

        $queryBuilder = $connection->createQueryBuilder();
        $deleted = $queryBuilder->delete('game_rounds')
            ->where('id = :id')
            ->setParameter('id', $deleteId)
            ->execute();

        $message = $deleted ? 'Record deleted successfully!' : 'Error: Record could not be deleted.';
    }

    $queryBuilder = $connection->createQueryBuilder();
    $rounds = $queryBuilder->select('*')
        ->from('game_rounds')
        ->orderBy('played_at', 'DESC')
        ->execute()
        ->fetchAllAssociative();

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
    <link rel="stylesheet" href="style.css">
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
    <a href="index.php" class="back-link">← Back to Add Game Round</a>
</div>
</body>
</html>

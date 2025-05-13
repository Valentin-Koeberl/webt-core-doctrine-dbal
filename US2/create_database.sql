CREATE DATABASE IF NOT EXISTS usarps_championship;
USE usarps_championship;

CREATE TABLE IF NOT EXISTS game_rounds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100) NOT NULL,
    symbol ENUM('Rock', 'Paper', 'Scissors') NOT NULL,
    round_number INT NOT NULL,
    played_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_player_name (player_name),
    INDEX idx_symbol (symbol),
    INDEX idx_played_at (played_at),
    INDEX idx_round_number (round_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
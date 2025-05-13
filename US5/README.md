# USARPS Championship - Add Game Round

This page allows you to add new game rounds to the USARPS Championship database using Doctrine DBAL.

## Features

- Modern, responsive form design with Apple-inspired aesthetics
- Form validation with specific error messages
- Automatic round number suggestion
- Date and time picker with current time default
- Form state persistence on validation errors
- Success/error message handling
- Secure database connection with PDO options
- Back link to game rounds list

## Setup Instructions

1. Make sure your MySQL server is running and the database is created:
```sql
CREATE DATABASE IF NOT EXISTS usarps_championship;
```

2. Run the database creation script from US2:
```bash
mysql -u root usarps_championship < ../US2/create_database.sql
```

3. Access the page through your web server:
```
http://localhost/US5/index.php
```

## Form Fields

- **Player Name**: Required text field
- **Symbol**: Required selection (Rock, Paper, Scissors)
- **Round Number**: Required number field, auto-suggested
- **Date and Time**: Required datetime field, defaults to current time

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Doctrine DBAL 3.7 or higher
- Composer (for dependency management)

## Security Features

- Input sanitization
- PDO prepared statements
- XSS prevention
- Form validation
- Secure database connection options 
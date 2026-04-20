#!/bin/bash
set -e

# Debug: show all paths and find PHP
echo "PATH: $PATH"
echo "Searching for PHP..."
find / -name "php" -type f 2>/dev/null | head -10

# Find PHP binary
PHP=$(find / -name "php" -type f 2>/dev/null | head -1)

if [ -z "$PHP" ]; then
    echo "PHP not found!"
    exit 1
fi

echo "Using PHP: $PHP"
$PHP --version

$PHP artisan migrate --force
$PHP artisan db:seed --class=AdminUserSeeder --force
$PHP artisan storage:link
$PHP artisan serve --host=0.0.0.0 --port=$PORT

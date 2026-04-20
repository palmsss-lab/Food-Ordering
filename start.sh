#!/bin/bash
set -e

# Find PHP binary
PHP=$(which php || find /nix -name "php" -type f 2>/dev/null | head -1)

echo "Using PHP: $PHP"
$PHP --version

$PHP artisan migrate --force
$PHP artisan db:seed --class=AdminUserSeeder --force
$PHP artisan storage:link
$PHP artisan serve --host=0.0.0.0 --port=$PORT

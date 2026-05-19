<?php
// Quick debug: show what happens when export.php is called
// Remove this file after debugging

require __DIR__ . '/../app/core/helpers.php';
require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../app/core/Session.php';
require __DIR__ . '/../app/core/Auth.php';

Session::start();

echo "<pre>";
echo "Auth::check() = " . (Auth::check() ? 'TRUE' : 'FALSE') . "\n";
echo "Auth::role() = " . Auth::role() . "\n";
echo "Auth::id() = " . Auth::id() . "\n";
echo "session_id() = " . session_id() . "\n";
echo "_SESSION keys: " . implode(', ', array_keys($_SESSION)) . "\n";
echo "</pre>";

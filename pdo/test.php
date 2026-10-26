<?php

require_once __DIR__ . '/Database.php';

try {
    $db = new Database();
    $pdo = $db->connect();
    echo "Connection successful! PostgreSQL server is ready.\n";
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}

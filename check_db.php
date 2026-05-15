<?php
require 'db.php';
try {
    $count = $pdo->query('SELECT COUNT(*) FROM pets')->fetchColumn();
    echo "PET_COUNT=" . $count;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}

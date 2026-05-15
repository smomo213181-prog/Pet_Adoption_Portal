<?php
require 'db.php';

function showTable($pdo, $name) {
    $row = $pdo->query("SHOW CREATE TABLE $name")->fetch(PDO::FETCH_ASSOC);
    echo "--- $name ---\n";
    echo $row['Create Table'] . "\n\n";
}

showTable($pdo, 'users');
showTable($pdo, 'pets');
showTable($pdo, 'favorites');

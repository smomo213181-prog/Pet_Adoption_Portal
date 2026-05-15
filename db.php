<?php

$host = '127.0.0.1';
$db   = 'pet_adoption_portal';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS pets (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        type ENUM('dog', 'cat') NOT NULL,
        breed VARCHAR(255),
        age_category ENUM('puppy', 'adult', 'senior') NOT NULL,
        gender ENUM('male', 'female') NOT NULL,
        location VARCHAR(255),
        description TEXT,
        image VARCHAR(255),
        created_at DATETIME NOT NULL,
        INDEX idx_type (type),
        INDEX idx_age_category (age_category)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $foreignKeys = $pdo->query("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pets' AND REFERENCED_TABLE_NAME IS NOT NULL")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($foreignKeys as $fk) {
        if ($fk['REFERENCED_TABLE_NAME'] === 'users' || $fk['COLUMN_NAME'] === 'user_id') {
            $pdo->exec("ALTER TABLE pets DROP FOREIGN KEY `" . $fk['CONSTRAINT_NAME'] . "`");
        }
    }

    $existingPetColumns = $pdo->query("SHOW COLUMNS FROM pets")->fetchAll(PDO::FETCH_COLUMN);
    $requiredPetColumns = [
        'type' => "type ENUM('dog','cat') NOT NULL AFTER name",
        'age_category' => "age_category ENUM('puppy','adult','senior') NOT NULL AFTER breed",
        'gender' => "gender ENUM('male','female') NOT NULL AFTER age_category",
        'description' => 'description TEXT NULL AFTER location',
        'image' => 'image VARCHAR(255) NULL AFTER description',
    ];

    foreach ($requiredPetColumns as $column => $definition) {
        if (!in_array($column, $existingPetColumns, true)) {
            $pdo->exec("ALTER TABLE pets ADD COLUMN $definition");
        }
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS favorites (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        pet_id INT UNSIGNED NOT NULL,
        created_at DATETIME NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
        UNIQUE KEY unique_favorite (user_id, pet_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $missingPetColumns = [];
    $existingPetColumns = $pdo->query("SHOW COLUMNS FROM pets")->fetchAll(PDO::FETCH_COLUMN);
    $requiredPetColumns = [
        'type' => "type ENUM('dog','cat') NOT NULL AFTER name",
        'age_category' => "age_category ENUM('puppy','adult','senior') NOT NULL AFTER breed",
        'gender' => "gender ENUM('male','female') NOT NULL AFTER age_category",
        'description' => 'description TEXT NULL AFTER location',
        'image' => 'image VARCHAR(255) NULL AFTER description',
    ];

    foreach ($requiredPetColumns as $column => $definition) {
        if (!in_array($column, $existingPetColumns, true)) {
            $missingPetColumns[$column] = $definition;
        }
    }

    foreach ($missingPetColumns as $definition) {
        $pdo->exec("ALTER TABLE pets ADD COLUMN $definition");
    }

    $count = $pdo->query('SELECT COUNT(*) FROM pets')->fetchColumn();
    if ($count == 0) {
        $stmt = $pdo->prepare('INSERT INTO pets (name, type, breed, age_category, gender, location, description, image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $samplePets = [
            ['Mochi', 'cat', 'Domestic Shorthair', 'adult', 'female', 'San Francisco, CA', 'A sweet and playful calico cat who loves cuddles.', 'images/pet-card-1.jpg'],
            ['Luna', 'cat', 'Ragdoll Mix', 'senior', 'female', 'Seattle, WA', 'A gentle senior cat who enjoys quiet companionship.', 'images/pet-card-2.jpg'],
            ['Snowball', 'dog', 'American Eskimo', 'puppy', 'female', 'Portland, OR', 'An energetic puppy who loves outdoor adventures.', 'images/pet-card-3.jpg'],
            ['Sylvester', 'cat', 'Maine Coon', 'adult', 'male', 'Los Angeles, CA', 'A majestic Maine Coon with a fluffy coat and friendly personality.', 'images/pet-card-1.jpg'],
        ];

        foreach ($samplePets as $pet) {
            $stmt->execute($pet);
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Database connection failed: ' . htmlspecialchars($e->getMessage());
    exit;
}

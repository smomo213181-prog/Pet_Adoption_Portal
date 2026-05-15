<?php
require_once __DIR__ . '/../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php?redirect=add_pet.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../add_pet.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$type = trim($_POST['type'] ?? '');
$breed = trim($_POST['breed'] ?? '');
$age_category = trim($_POST['age_category'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$location = trim($_POST['location'] ?? '');
$description = trim($_POST['description'] ?? '');
$image = trim($_POST['image'] ?? '');

$errors = [];

if ($name === '') {
    $errors[] = 'Pet name is required.';
}
if (!in_array($type, ['dog', 'cat'], true)) {
    $errors[] = 'Pet type is required.';
}
if (!in_array($age_category, ['puppy', 'adult', 'senior'], true)) {
    $errors[] = 'Age category is required.';
}
if (!in_array($gender, ['male', 'female'], true)) {
    $errors[] = 'Gender is required.';
}
if ($location === '') {
    $errors[] = 'Location is required.';
}

if (!empty($errors)) {
    $query = http_build_query(['error' => implode(' ', $errors)]);
    header('Location: ../add_pet.php?' . $query);
    exit;
}

if ($image === '') {
    $image = 'images/pet-card-1.jpg';
}

$stmt = $pdo->prepare('INSERT INTO pets (name, type, breed, age_category, gender, location, description, image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
$stmt->execute([$name, $type, $breed, $age_category, $gender, $location, $description, $image]);

header('Location: ../index.php');
exit;
?>
<?php
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Pet_Adoption_Portal/signup.php');
    exit;
}

$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];

if ($firstName === '') {
    $errors[] = 'First name is required.';
}
if ($lastName === '') {
    $errors[] = 'Last name is required.';
}
if ($email === '') {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email is not valid.';
}
if ($password === '') {
    $errors[] = 'Password is required.';
}

if (empty($errors)) {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $existing = $stmt->fetch();

    if ($existing) {
        $errors[] = 'A user with that email already exists.';
    }
}

if (!empty($errors)) {
    $query = http_build_query(['error' => implode(' ', $errors)]);
    header('Location: /Pet_Adoption_Portal/signup.php?' . $query);
    exit;
}

$fullName = $firstName . ' ' . $lastName;
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$createdAt = date('Y-m-d H:i:s');

$stmt = $pdo->prepare('INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, ?)');
$stmt->execute([$fullName, $email, $hashedPassword, $createdAt]);

header('Location: /Pet_Adoption_Portal/login.php?registered=1');
exit;

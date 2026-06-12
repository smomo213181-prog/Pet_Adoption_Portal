<?php
require_once __DIR__ . '/../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php?redirect=my_pets.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../my_pets.php');
    exit;
}

$petId = isset($_POST['pet_id']) ? (int)$_POST['pet_id'] : 0;
$user_id = $_SESSION['user_id'];

if ($petId <= 0) {
    header('Location: ../my_pets.php?error=' . urlencode('Invalid pet selection.'));
    exit;
}

$stmt = $pdo->prepare('DELETE FROM pets WHERE id = ? AND user_id = ?');
$stmt->execute([$petId, $user_id]);

if ($stmt->rowCount() === 0) {
    header('Location: ../my_pets.php?error=' . urlencode('Pet not found or permission denied.'));
    exit;
}

header('Location: ../my_pets.php?success=' . urlencode('Pet deleted successfully.'));
exit;

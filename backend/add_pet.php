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
$imageFile = $_FILES['image_file'] ?? null;

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

$uploadPath = __DIR__ . '/../images/uploads';
if (!is_dir($uploadPath)) {
    mkdir($uploadPath, 0755, true);
}

if ($imageFile && isset($imageFile['error']) && $imageFile['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfo, $imageFile['tmp_name']);
    finfo_close($fileInfo);

    if (!array_key_exists($mimeType, $allowedTypes)) {
        $errors[] = 'Uploaded image must be JPG, PNG, GIF, or WEBP.';
    } else {
        $extension = $allowedTypes[$mimeType];
        $filename = 'pet-' . uniqid() . '.' . $extension;
        $destination = $uploadPath . '/' . $filename;

        if (!move_uploaded_file($imageFile['tmp_name'], $destination)) {
            $errors[] = 'Failed to save uploaded image. Please try again.';
        } else {
            $image = 'images/uploads/' . $filename;
        }
    }
} elseif ($image !== '') {
    $image = trim($image);
}

if ($image === '') {
    $image = 'images/pet-card-1.jpg';
}

if (!empty($errors)) {
    $query = http_build_query(['error' => implode(' ', $errors)]);
    header('Location: ../add_pet.php?' . $query);
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('INSERT INTO pets (user_id, name, type, breed, age_category, gender, location, description, image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
$stmt->execute([$user_id, $name, $type, $breed, $age_category, $gender, $location, $description, $image]);

header('Location: ../my_pets.php?success=' . urlencode('Pet added successfully.'));
exit;
?>
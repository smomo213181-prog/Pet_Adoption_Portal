<?php
require_once __DIR__ . '/../db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=browse_pets.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $content_type = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (strpos($content_type, 'application/json') !== false) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents('php://input'), true);
            $pet_id = $data['pet_id'] ?? 0;
            $action = $data['action'] ?? '';

            if (!$pet_id || !in_array($action, ['add', 'remove'])) {
                echo json_encode(['success' => false, 'error' => 'Invalid request']);
                exit;
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare('INSERT IGNORE INTO favorites (user_id, pet_id, created_at) VALUES (?, ?, NOW())');
                $stmt->execute([$user_id, $pet_id]);
                echo json_encode(['success' => true, 'action' => 'added']);
            } else {
                $stmt = $pdo->prepare('DELETE FROM favorites WHERE user_id = ? AND pet_id = ?');
                $stmt->execute([$user_id, $pet_id]);
                echo json_encode(['success' => true, 'action' => 'removed']);
            }
        } else {
            // Form submission
            $pet_id = isset($_POST['pet_id']) ? (int)$_POST['pet_id'] : 0;
            
            if (!$pet_id) {
                header('Location: browse_pets.php');
                exit;
            }

            // Check if already favorited
            $stmt = $pdo->prepare('SELECT id FROM favorites WHERE user_id = ? AND pet_id = ?');
            $stmt->execute([$user_id, $pet_id]);
            $favorite = $stmt->fetch();

            if ($favorite) {
                // Remove from favorites
                $stmt = $pdo->prepare('DELETE FROM favorites WHERE user_id = ? AND pet_id = ?');
                $stmt->execute([$user_id, $pet_id]);
            } else {
                // Add to favorites
                $stmt = $pdo->prepare('INSERT IGNORE INTO favorites (user_id, pet_id, created_at) VALUES (?, ?, NOW())');
                $stmt->execute([$user_id, $pet_id]);
            }

            // Redirect back to referring page
            $referer = $_SERVER['HTTP_REFERER'] ?? 'browse_pets.php';
            header('Location: ' . $referer);
            exit;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Get user's favorites
        header('Content-Type: application/json');
        $stmt = $pdo->prepare('
            SELECT p.* FROM pets p
            INNER JOIN favorites f ON p.id = f.pet_id
            WHERE f.user_id = ?
            ORDER BY f.created_at DESC
        ');
        $stmt->execute([$user_id]);
        $favorites = $stmt->fetchAll();

        echo json_encode(['success' => true, 'favorites' => $favorites]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
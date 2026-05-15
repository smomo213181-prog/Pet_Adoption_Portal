<?php
require_once __DIR__ . '/../db.php';

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Get user's favorites
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
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
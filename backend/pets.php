<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

try {
    $type = $_GET['type'] ?? '';
    $breed = $_GET['breed'] ?? '';
    $age_category = $_GET['age_category'] ?? '';

    $query = 'SELECT * FROM pets WHERE 1=1';
    $params = [];

    if ($type) {
        $query .= ' AND type = ?';
        $params[] = $type;
    }
    if ($breed) {
        $query .= ' AND breed LIKE ?';
        $params[] = '%' . $breed . '%';
    }
    if ($age_category) {
        $query .= ' AND age_category = ?';
        $params[] = $age_category;
    }

    $query .= ' ORDER BY created_at DESC';

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $pets = $stmt->fetchAll();

    echo json_encode(['success' => true, 'pets' => $pets]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
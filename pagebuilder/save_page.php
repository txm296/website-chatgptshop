<?php
session_start();
if (!isset($_SESSION['admin'])) { http_response_code(403); exit('Forbidden'); }
require __DIR__ . '/../inc/db.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid']);
    exit;
}

$title = $input['title'] ?? '';
$slug  = isset($input['slug']) ? preg_replace('/[^a-z0-9-]/', '-', strtolower(trim($input['slug']))) : '';
$layout = json_encode(['html' => $input['layout'] ?? ''], JSON_UNESCAPED_UNICODE);
$id = isset($input['id']) ? (int)$input['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare('UPDATE builder_pages SET title=?, slug=?, layout=? WHERE id=?');
    $stmt->execute([$title, $slug, $layout, $id]);
} else {
    $stmt = $pdo->prepare('INSERT INTO builder_pages (title, slug, layout) VALUES (?,?,?)');
    $stmt->execute([$title, $slug, $layout]);
    $id = $pdo->lastInsertId();
}

echo json_encode(['id' => $id]);

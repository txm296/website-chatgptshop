<?php
session_start();
if (!isset($_SESSION['admin'])) { http_response_code(403); exit('Forbidden'); }
require __DIR__ . '/../inc/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slug = $_GET['slug'] ?? '';
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM builder_pages WHERE id=?');
    $stmt->execute([$id]);
} elseif ($slug !== '') {
    $stmt = $pdo->prepare('SELECT * FROM builder_pages WHERE slug=?');
    $stmt->execute([$slug]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'missing']);
    exit;
}
$page = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$page) { http_response_code(404); echo json_encode(['error'=>'not found']); exit; }
$data = json_decode($page['layout'], true);
$layout = $data['html'] ?? '';

echo json_encode([
    'id' => $page['id'],
    'title' => $page['title'],
    'slug' => $page['slug'],
    'layout' => $layout
]);

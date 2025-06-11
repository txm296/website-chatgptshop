<?php
session_start();
if (!isset($_SESSION['admin'])) { http_response_code(403); exit('Forbidden'); }
require __DIR__ . '/../inc/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slug = $_GET['slug'] ?? '';
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM builder_popups WHERE id=?');
    $stmt->execute([$id]);
} elseif ($slug !== '') {
    $stmt = $pdo->prepare('SELECT * FROM builder_popups WHERE slug=?');
    $stmt->execute([$slug]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'missing']);
    exit;
}
$popup = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$popup) { http_response_code(404); echo json_encode(['error'=>'not found']); exit; }
$data = json_decode($popup['layout'], true);

echo json_encode([
    'id' => $popup['id'],
    'title' => $popup['title'],
    'slug' => $popup['slug'],
    'layout' => $data['html'] ?? '',
    'triggers' => $popup['triggers'] ? json_decode($popup['triggers'], true) : [],
    'pages' => trim($popup['pages'], ',')
]);

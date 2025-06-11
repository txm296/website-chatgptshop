<?php
session_start();
if (!isset($_SESSION['admin'])) { http_response_code(403); exit('Forbidden'); }
require __DIR__ . '/../inc/db.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) { http_response_code(400); echo json_encode(['error'=>'invalid']); exit; }

$title = $input['title'] ?? '';
$slug = isset($input['slug']) ? preg_replace('/[^a-z0-9-]/','-', strtolower(trim($input['slug']))) : '';
$layout = json_encode(['html' => $input['layout'] ?? ''], JSON_UNESCAPED_UNICODE);
$triggers = json_encode($input['triggers'] ?? [], JSON_UNESCAPED_UNICODE);
$pages = '';
if (!empty($input['pages']) && is_array($input['pages'])) {
    $clean = array_map(function($s){ return preg_replace('/[^a-z0-9-]/','-', strtolower(trim($s))); }, $input['pages']);
    $clean = array_filter($clean);
    if ($clean) $pages = ',' . implode(',', $clean) . ',';
}
$id = isset($input['id']) ? (int)$input['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare('UPDATE builder_popups SET title=?, slug=?, layout=?, triggers=?, pages=? WHERE id=?');
    $stmt->execute([$title, $slug, $layout, $triggers, $pages, $id]);
} else {
    $stmt = $pdo->prepare('INSERT INTO builder_popups (title, slug, layout, triggers, pages) VALUES (?,?,?,?,?)');
    $stmt->execute([$title, $slug, $layout, $triggers, $pages]);
    $id = $pdo->lastInsertId();
}

echo json_encode(['id'=>$id]);

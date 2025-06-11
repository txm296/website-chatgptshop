<?php
require __DIR__ . '/../inc/db.php';
$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM builder_popups WHERE pages='' OR pages LIKE ?");
$stmt->execute(['%,'. $slug .',%']);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$out = [];
foreach ($rows as $row) {
    $data = json_decode($row['layout'], true);
    $out[] = [
        'id' => $row['id'],
        'html' => $data['html'] ?? '',
        'triggers' => $row['triggers'] ? json_decode($row['triggers'], true) : []
    ];
}
header('Content-Type: application/json');
echo json_encode($out);

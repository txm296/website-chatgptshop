<?php
session_start();
if (!isset($_SESSION['admin'])) { http_response_code(403); exit('Forbidden'); }
require __DIR__ . '/../inc/db.php';
$input = json_decode(file_get_contents('php://input'), true);
$slug = $input['slug'] ?? '';
$layout = $input['layout'] ?? '';
$meta = ['title'=>'','meta_description'=>'','canonical_url'=>'','jsonld'=>''];
if ($slug) {
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE slug=?');
    $stmt->execute([$slug]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($page) {
        $meta['title'] = $page['meta_title'] ?: $page['title'];
        $meta['meta_description'] = $page['meta_description'] ?? '';
        $meta['canonical_url'] = $page['canonical_url'] ?? '';
        $meta['jsonld'] = $page['jsonld'] ?? '';
    }
}
$pageTitle = $meta['title'];
$metaDescription = $meta['meta_description'];
$canonicalUrl = $meta['canonical_url'];
$jsonLd = $meta['jsonld'];
$active = $slug;
$currentSlug = $slug;
ob_start();
include __DIR__ . '/../inc/header.php';
echo $layout;
include __DIR__ . '/../inc/footer.php';
$html = ob_get_clean();
header('Content-Type: text/html; charset=utf-8');
echo $html;
?>

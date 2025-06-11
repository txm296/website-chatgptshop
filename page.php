<?php
require 'inc/db.php';
$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare('SELECT * FROM pages WHERE slug = ?');
$stmt->execute([$slug]);
$page = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$page) {
    http_response_code(404);
    $pageTitle = 'Seite nicht gefunden';
    $active = '';
    include 'inc/header.php';
    echo '<section class="py-24 text-center"><h2 class="text-3xl font-bold mb-4">Seite nicht gefunden</h2></section>';
    include 'inc/footer.php';
    exit;
}
$pageTitle = $page['meta_title'] ?: $page['title'];
$metaDescription = $page['meta_description'] ?? '';
$canonicalUrl = $page['canonical_url'] ?? '';
$jsonLd = $page['jsonld'] ?? '';
$active = $slug;
include 'inc/header.php';
echo '<section class="py-24 max-w-3xl mx-auto">'.$page['content'].'</section>';
include 'inc/footer.php';
?>

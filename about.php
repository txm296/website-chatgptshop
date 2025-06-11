<?php
$active = 'about';
require 'inc/db.php';
require_once 'inc/pagebuilder.php';
$stmt = $pdo->prepare('SELECT title, content, meta_title, meta_description, canonical_url, jsonld FROM pages WHERE slug = ?');
$stmt->execute(['about']);
$page = $stmt->fetch(PDO::FETCH_ASSOC);
$pageTitle = $page['meta_title'] ?: ($page['title'] ?? 'Über nezbi');
$metaDescription = $page['meta_description'] ?? '';
$canonicalUrl = $page['canonical_url'] ?? '';
$jsonLd = $page['jsonld'] ?? '';
require_once "inc/template.php";
$builderLayout = null;
if (!isset($_GET['classic'])) {
    $builderLayout = get_builder_layout($pdo, 'about');
}
include 'inc/header.php';
if ($builderLayout) {
    echo $builderLayout;
    include 'inc/footer.php';
    return;
}
if ($page) {
    echo $page['content'];
} else {
    ?>
    <section class="py-24 text-center">
        <h2 class="text-3xl font-bold mb-4">&Uuml;ber nezbi</h2>
        <p class="max-w-3xl mx-auto text-gray-600">Dein Onlineshop f&uuml;r ausgew&auml;hlte Technikprodukte. Wir lieben hochwertiges Design und leistungsstarke Hardware.</p>
    </section>
    <?php
}
render_template("cta");
include 'inc/footer.php';
?>

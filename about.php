<?php
$active = 'about';
require 'inc/db.php';
$stmt = $pdo->prepare('SELECT title, content FROM pages WHERE slug = ?');
$stmt->execute(['about']);
$page = $stmt->fetch(PDO::FETCH_ASSOC);
$pageTitle = $page['title'] ?? 'Über nezbi';
require_once "inc/template.php";
include 'inc/header.php';
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

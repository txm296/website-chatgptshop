<?php
$active = 'home';
$pageTitle = 'nezbi – Elektronik Onlineshop';
require 'inc/db.php';
require_once 'inc/pagebuilder.php';
require 'inc/settings.php';
$siteSettings = load_settings();
require_once "inc/template.php";

$builderLayout = null;
if (!isset($_GET['classic'])) {
    $builderLayout = get_builder_layout($pdo, 'home');
}
include 'inc/header.php';
if ($builderLayout) {
    echo $builderLayout;
    include 'inc/footer.php';
    return;
}
?>
<section class="relative text-center py-24 text-white" style="background-image:url('<?= htmlspecialchars($siteSettings['hero_image']) ?>'); background-size:cover; background-position:center;">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-6"><?= htmlspecialchars($siteSettings['hero_title']) ?></h1>
        <p class="text-xl md:text-2xl mb-8"><?= htmlspecialchars($siteSettings['hero_subtitle']) ?></p>
        <a href="/produkte.php" class="px-8 py-3 rounded-xl accent-bg text-white hover:opacity-90 transition">Jetzt entdecken</a>
    </div>
</section>
<section class="grid grid-cols-1 md:grid-cols-3 gap-8 py-16">
    <div class="text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h3l39v11a1 1 0 01-1 1h-2a1 1 0 01-1-1v-5H5a1 1 0 01-1-1V4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4h3a1 1 0 011 1v13a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
        <h3 class="text-lg font-semibold mt-4 mb-2">Schneller Versand</h3>
        <p class="text-gray-600">Innerhalb von 24h bei dir zu Hause</p>
    </div>
    <div class="text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.793-4s-1.343-4-3-4-3 1.793-3 4 1.343 4 3 4zM12 14.93v7.07"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.343 14.657A8 8 0 1117.657 3.343"/></svg>
        <h3 class="text-lg font-semibold mt-4 mb-2">Zeitloses Design</h3>
        <p class="text-gray-600">Minimalistisch und ansprechend</p>
    </div>
    <div class="text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1l3 9H9l3-9zM12 22v-7"/></svg>
        <h3 class="text-lg font-semibold mt-4 mb-2">Sichere Zahlung</h3>
        <p class="text-gray-600">Verschl&uuml;sselte Transaktionen</p>
    </div>
</section>
<?php
$trending = $pdo->query("SELECT * FROM produkte WHERE aktiv=1 ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
if ($trending): ?>
<section class="py-16">
    <h2 class="text-3xl font-bold mb-8 text-center">Beliebte Produkte</h2>
    <div class="grid gap-8 grid-cols-1 md:grid-cols-3">
    <?php foreach ($trending as $prod): ?>
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition">
            <div class="relative w-40 h-40 mb-4">
                <?php if ($prod['rabatt'] && $prod['rabatt'] < $prod['preis']): ?>
                    <span class="absolute top-1 left-1 z-10 bg-red-600 text-white text-xs px-2 py-1 rounded-br">Angebot</span>
                <?php endif; ?>
                <img src="<?=htmlspecialchars($prod['bild'])?>" alt="<?=htmlspecialchars($prod['name'])?>" class="w-full h-full object-contain" />
            </div>
            <div class="font-bold text-lg mb-1 text-center break-words"><?=htmlspecialchars($prod['name'])?></div>
            <div class="font-mono text-lg mb-4">
                <?php if ($prod['rabatt'] && $prod['rabatt'] < $prod['preis']): ?>
                    <span class="line-through mr-2 text-gray-500"><?=number_format($prod['preis'],2,',','.')?> €</span>
                    <span class="text-red-600 font-bold"><?=number_format($prod['rabatt'],2,',','.')?> €</span>
                <?php else: ?>
                    <?=number_format($prod['preis'],2,',','.')?> €
                <?php endif; ?>
            </div>
            <a href="produkt.php?id=<?=$prod['id']?>" class="mt-auto px-5 py-2 rounded-xl accent-bg text-white font-semibold tracking-wide hover:shadow-lg transition">Details</a>
        </div>
    <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
render_template("cta");
<?php include 'inc/footer.php'; ?>

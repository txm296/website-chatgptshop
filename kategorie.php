<?php
require 'inc/db.php';
require_once 'inc/pagebuilder.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM kategorien WHERE id=?");
$stmt->execute([$id]);
$kategorie = $stmt->fetch();
if (!$kategorie) { http_response_code(404); exit('Kategorie nicht gefunden.'); }
$active = 'produkte';
$pageTitle = htmlspecialchars($kategorie['name']) . ' – nezbi';
$builderLayout = null;
if (!isset($_GET['classic'])) {
    $builderLayout = get_builder_layout($pdo, 'category-' . $id);
}
include 'inc/header.php';
if ($builderLayout) {
    echo $builderLayout;
    include 'inc/footer.php';
    return;
}
?>
<section class="py-16">
    <h2 class="text-3xl font-bold mb-8 text-center"><?= htmlspecialchars($kategorie['name']) ?></h2>
    <div class="grid gap-8 grid-cols-1 md:grid-cols-3">
    <?php
    $stmt = $pdo->prepare("SELECT * FROM produkte WHERE aktiv=1 AND kategorie_id=? ORDER BY id DESC");
    $stmt->execute([$kategorie['id']]);
    foreach ($stmt as $prod):
    ?>
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition">
            <div class="relative w-40 h-40 mb-4">
                <?php if ($prod['rabatt'] && $prod['rabatt'] < $prod['preis']): ?>
                    <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded-br">Angebot</span>
                <?php endif; ?>
                <img src="<?=htmlspecialchars($prod['bild'])?>" alt="<?=htmlspecialchars($prod['name'])?>" class="w-full h-full object-contain" />
            </div>
            <div class="font-bold text-lg mb-1"><?=htmlspecialchars($prod['name'])?></div>
            <div class="text-gray-500 mb-2"><?=htmlspecialchars($prod['beschreibung'])?></div>
            <div class="font-mono text-lg mb-4">
                <?php if ($prod['rabatt'] && $prod['rabatt'] < $prod['preis']): ?>
                    <span class="line-through mr-2 text-gray-500"><?=number_format($prod['preis'],2,',','.')?> €</span>
                    <span class="text-red-600 font-bold"><?=number_format($prod['rabatt'],2,',','.')?> €</span>
                <?php else: ?>
                    <?=number_format($prod['preis'],2,',','.')?> €
                <?php endif; ?>
            </div>
            <a href="produkt.php?id=<?=$prod['id']?>" class="mt-auto px-5 py-2 rounded-xl accent-bg text-white font-semibold tracking-wide accent-bg-hover transition">Details</a>
        </div>
    <?php endforeach; ?>
    </div>
</section>
<?php include 'inc/footer.php'; ?>

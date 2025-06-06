<?php
require 'inc/db.php';
$active = 'produkte';
$pageTitle = 'Produkte – nezbi';
include 'inc/header.php';
?>
<section class="py-16">
    <h2 class="text-3xl font-bold mb-8 text-center">Unsere Elektronik-Highlights</h2>
    <?php
    $kats = $pdo->query("SELECT * FROM kategorien ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($kats as $kat):
        $stmt = $pdo->prepare("SELECT * FROM produkte WHERE aktiv=1 AND kategorie_id=? ORDER BY id DESC");
        $stmt->execute([$kat['id']]);
        $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$prods) continue;
    ?>
        <h3 class="text-2xl font-semibold mb-6 mt-12"><?= htmlspecialchars($kat['name']) ?></h3>
        <div class="grid gap-8 grid-cols-1 md:grid-cols-3">
        <?php foreach ($prods as $prod): ?>
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
                <a href="produkt.php?id=<?=$prod['id']?>" class="mt-auto px-5 py-2 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 text-white font-semibold tracking-wide hover:shadow-lg transition">Details</a>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</section>
<?php include 'inc/footer.php'; ?>
